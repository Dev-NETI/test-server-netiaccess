<?php

namespace App\Traits;

use Exception;

trait QueryTrait
{
    public function storeTrait($query, $errorMsg, $successMsg)
    {
        try {
            if (!$query) {
                session()->flash('error', $errorMsg);
            }
            session()->flash('success', $successMsg);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateTrait($data, $routeBack, $query, $errorMsg, $successMsg)
    {
        if (!$data) {
            session()->flash('error', 'Data not found');
            // return $this->redirectRoute($routeBack);
        }
        $this->storeTrait($query, $errorMsg, $successMsg);
    }

    public function updateTraitNoRoute($data, $query, $errorMsg, $successMsg)
    {
        if (!$data) {
            return session()->flash('error', 'Data not found');
        }
        $this->storeTrait($query, $errorMsg, $successMsg);
    }
}
