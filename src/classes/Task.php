<?php

class Task
{

    private string $title;
    private bool $isDone;

    public function getTitle()
    {
        return $this->title;
    }

    public function getIsDone()
    {
        return $this->isDone;
    }

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->isDone = false;
    }

    // methode permettant de changer l'état de la tâche
    public function toggle()
    {
        if ($this->isDone == false) {
            $this->isDone = true;
        } else {
            $this->isDone = false;
        }
    }

    // méthode qui est appelée automatiquement lors d'un echo
    public function __toString()
    {
        // je fais en sorte de return ma structure html / css.
        // je ne vais pas m'en servir dans ce TD :p
        return '<div class="my-2">
                 <a href="action.php?action=add" class="btn btn-outline-primary">' . $this->title . '<i class="bi bi-check"></i></a>
                 <a href="action.php?action=delete" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></a>
                </div>';
    }
}
