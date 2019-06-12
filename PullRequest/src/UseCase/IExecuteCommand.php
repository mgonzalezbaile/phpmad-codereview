<?php


namespace App\UseCase;


interface IExecuteCommand
{
    public function execute(Command $command);
}
