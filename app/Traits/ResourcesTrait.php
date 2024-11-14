<?php

namespace App\Traits;

use Exception;

trait ResourcesTrait
{
    public function storeTrait($model, array $attributes, $errorMsg, $successMsg, $exceptionMessage = null)
    {
        try {
            $store = $model::create($attributes);
            if (!$store) {
                session()->flash('error', $errorMsg);
            }
            session()->flash('success', $successMsg);
        } catch (Exception $e) {
            $excMsg = $exceptionMessage != null ? $exceptionMessage : $e->getMessage();
            session()->flash('error', $excMsg);
        }
    }

    public function updateTrait($model, array $attributes, $errorMsg, $successMsg, $exceptionMessage = null)
    {
        try {
            $update = $model->update($attributes);
            if (!$update) {
                session()->flash('error', $errorMsg);
            }
            session()->flash('success', $successMsg);
        } catch (Exception $e) {
            $excMsg = $exceptionMessage != null ? $exceptionMessage : $e->getMessage();
            session()->flash('error', $excMsg);
        }
    }
}
