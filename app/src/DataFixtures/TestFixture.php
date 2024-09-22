<?php

namespace App\DataFixtures;

use App\Entity\Quiz\AnswerOption;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $test = new Test();
        $test->setName('test_magic_text');
        $manager->persist($test);

        $questionsData = [
            '1 + 1 =' => [
                ['text' => '3', 'isCorrect' => false, 'bitMask' => 1],
                ['text' => '2', 'isCorrect' => true, 'bitMask' => 2],
                ['text' => '0', 'isCorrect' => false, 'bitMask' => 4],
            ],
            '2 + 2 =' => [
                ['text' => '4', 'isCorrect' => true, 'bitMask' => 1],
                ['text' => '3 + 1', 'isCorrect' => true, 'bitMask' => 2],
                ['text' => '10', 'isCorrect' => false, 'bitMask' => 4],
            ],
            '3 + 3 =' => [
                ['text' => '1 + 5', 'isCorrect' => true, 'bitMask' => 1],
                ['text' => '1', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '6', 'isCorrect' => true, 'bitMask' => 4],
                ['text' => '2 + 4', 'isCorrect' => true, 'bitMask' => 8],
            ],
            '4 + 4 =' => [
                ['text' => '8', 'isCorrect' => true, 'bitMask' => 1],
                ['text' => '4', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '0', 'isCorrect' => false, 'bitMask' => 4],
                ['text' => '0 + 8', 'isCorrect' => true, 'bitMask' => 8],
            ],
            '5 + 5 =' => [
                ['text' => '6', 'isCorrect' => false, 'bitMask' => 1],
                ['text' => '18', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '10', 'isCorrect' => true, 'bitMask' => 4],
                ['text' => '9', 'isCorrect' => false, 'bitMask' => 8],
                ['text' => '0', 'isCorrect' => false, 'bitMask' => 16],
            ],
            '6 + 6 =' => [
                ['text' => '3', 'isCorrect' => false, 'bitMask' => 1],
                ['text' => '9', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '0', 'isCorrect' => false, 'bitMask' => 4],
                ['text' => '12', 'isCorrect' => true, 'bitMask' => 8],
                ['text' => '5 + 7', 'isCorrect' => true, 'bitMask' => 16],
            ],
            '7 + 7 =' => [
                ['text' => '5', 'isCorrect' => false, 'bitMask' => 1],
                ['text' => '14', 'isCorrect' => true, 'bitMask' => 2],
            ],
            '8 + 8 =' => [
                ['text' => '16', 'isCorrect' => true, 'bitMask' => 1],
                ['text' => '12', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '9', 'isCorrect' => false, 'bitMask' => 4],
                ['text' => '5', 'isCorrect' => false, 'bitMask' => 8],
            ],
            '9 + 9 =' => [
                ['text' => '18', 'isCorrect' => true, 'bitMask' => 1],
                ['text' => '9', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '17 + 1', 'isCorrect' => true, 'bitMask' => 4],
                ['text' => '2 + 16', 'isCorrect' => true, 'bitMask' => 8],
            ],
            '10 + 10 =' => [
                ['text' => '0', 'isCorrect' => false, 'bitMask' => 1],
                ['text' => '2', 'isCorrect' => false, 'bitMask' => 2],
                ['text' => '8', 'isCorrect' => false, 'bitMask' => 4],
                ['text' => '20', 'isCorrect' => true, 'bitMask' => 8],
            ],
        ];

        foreach ($questionsData as $questionText => $answers) {
            $question = new Question();
            $question->setText($questionText);

            $test->addQuestion($question);
            $manager->persist($question);

            foreach ($answers as $answerData) {
                $answerOption = new AnswerOption();
                $answerOption->setText($answerData['text']);
                $answerOption->setIsCorrect($answerData['isCorrect']);
                $answerOption->setBitMask($answerData['bitMask']);
                $answerOption->setQuestion($question);
                $manager->persist($answerOption);
            }
        }

        $manager->flush();
    }
}