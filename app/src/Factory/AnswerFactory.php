<?php

namespace App\Factory;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\AnswerOption;
use App\Entity\Quiz\Attempt;

class AnswerFactory
{
    public function create(Attempt $attempt, AnswerOption $option): Answer
    {
        $answer = new Answer();
        $answer->setAttempt($attempt);
        $answer->setAnswerOption($option);

        return $answer;
    }
}
