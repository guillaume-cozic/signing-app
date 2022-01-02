<?php

namespace App\Signing\School\Domain\Model;

class InternshipSession
{
    public function __construct(private array $sessions)
    {
        $numberOfSailingSessions = count($sessions);

        foreach($sessions as $key => $currentSession){
            $currentSession->check();

            for($i = $key+1; $i < $numberOfSailingSessions; $i++) {
                $currentSession->checkOverlapWithSession($sessions[$i]);
            }
        }
    }
}
