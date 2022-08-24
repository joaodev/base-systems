<?php 

namespace App\Interfaces;

interface CrudInterface
{
	public function createAction();
	public function readAction();
	public function updateAction();
	public function deleteAction();
}