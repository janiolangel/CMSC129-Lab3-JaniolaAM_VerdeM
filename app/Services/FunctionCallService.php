<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\TaskAPIController;

class FunctionCallService
{
    public function handle($json)
    {
        $action = $json['action'] ?? 'unknown';
        $data = $json['data'] ?? [];

        switch ($action) {

            case 'create_task':
                return $this->createTask($data);

            case 'update_task':
                return $this->prepareUpdate($data);

            case 'confirm_update':
                return $this->confirmUpdate();

            case 'delete_task':
                return $this->prepareArchive($data);

            case 'confirm_archive':
                return $this->confirmArchive();

            case 'restore_task':
                return $this->restoreTask($data);

            case 'force_delete_task':
                return $this->prepareForceDelete($data);

            case 'confirm_force_delete':
                return $this->confirmForceDelete();

            case 'query_tasks':
                return $this->queryTasks($data);

            case 'list_tasks':
                return $this->queryTasks([]);

            case 'count_tasks':
                return $this->countTasks($data);

            case 'oldest_task':
                return $this->oldestTask($data);

            default:
                return "I’m not quite sure what you mean. Can you rephrase that?";
        }
    }

    private function createTask($data)
    {
        (new TaskAPIController())->store(new Request($data));
        return "Got it. I’ve added that task.\n" . $this->queryTasks([]);
    }

    private function prepareUpdate($data)
    {
        session(['pending_update' => $data]);
        return "Do you want me to update this task? Type \"confirm update\" to proceed.";
    }

    private function confirmUpdate()
    {
        $data = session('pending_update');

        if (!$data || !isset($data['id'])) {
            return "There’s no update waiting to be confirmed.";
        }

        (new TaskAPIController())->update(new Request($data), $data['id']);
        session()->forget('pending_update');

        return "Alright, I’ve updated the task.\n" . $this->queryTasks([]);
    }

    private function prepareArchive($data)
    {
        session(['pending_archive' => $data['id'] ?? null]);
        return "Do you want me to archive this task? Type \"confirm archive\" to continue.";
    }

    private function confirmArchive()
    {
        $id = session('pending_archive');

        if (!$id) return "No pending archive.";

        (new TaskAPIController())->archive($id);
        session()->forget('pending_archive');

        return "Task archived.\n" . $this->queryTasks([]);
    }

    private function restoreTask($data)
    {
        if (!isset($data['id'])) return "Task ID required.";

        (new TaskAPIController())->restore($data['id']);
        return "Task restored.\n" . $this->queryTasks([]);
    }

    private function prepareForceDelete($data)
    {
        session(['pending_force_delete' => $data['id'] ?? null]);
        return "This will permanently delete the task. Type \"confirm delete forever\" if you’re sure you want to proceed.";
    }

    private function confirmForceDelete()
    {
        $id = session('pending_force_delete');

        if (!$id) return "No pending delete.";

        (new TaskAPIController())->forceDelete($id);
        session()->forget('pending_force_delete');

        return "Task permanently deleted.\n" . $this->queryTasks([]);
    }

    private function queryTasks($data)
    {
        $lastFilters = session('last_filters', []);

        if (empty($data)) {
            session()->forget('last_filters');
            $filters = [];
        } else {
            $filters = array_merge($lastFilters, $data);
            session(['last_filters' => $filters]);
        }

        $response = (new TaskAPIController())->index(new Request($filters));
        $tasks = $response->getData(true);

        if (empty($tasks)) return "I couldn’t find anything that matches that.";

        $count = count($tasks);

        $output = "I found {$count} task" . ($count > 1 ? "s" : "") . ":\n\n";

        foreach ($tasks as $task) {
            $state = !empty($task['deleted_at']) ? "Archived" : "Active";
            $status = $task['status'] ? "Done" : "Pending";

            $output .= "{$task['task']} ({$task['priority']}) - {$status} - {$state}\n";
        }

        return $output;
    }

    private function countTasks($data)
    {
        $tasks = (new TaskAPIController())->index(new Request($data))->getData(true);
        return "Total tasks: " . count($tasks);
    }

    private function oldestTask($data)
    {
        $tasks = collect((new TaskAPIController())->index(new Request($data))->getData(true));

        if ($tasks->isEmpty()) return "No tasks found.";

        $oldest = $tasks->sortBy('created_at')->first();

        return "Your oldest task is \"{$oldest['task']}\"";
    }
}