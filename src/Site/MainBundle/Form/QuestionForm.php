<?php

namespace Site\MainBundle\Form;

class QuestionForm
{

    /**
     * Имя
     *
     * @var
     */
    private $name;

    /**
     * Email
     *
     * @var
     */
    private $email;

    /**
     * Вопрос
     *
     * @var
     */
    private $question;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }
}
