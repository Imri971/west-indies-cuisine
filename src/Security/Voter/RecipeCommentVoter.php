<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RecipeCommentVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Comments;
    }

    protected function voteOnAttribute($attribute, $comments, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if (null == $comments->getAuthor()) {
            return false;
        }
        switch ($attribute) {
            case 'EDIT':
                return $comments->getAuthor() == $user->getId();
                break;
            case 'DELETE':
                return $comments->getAuthor()== $user->getId();
                break;
        }

        return false;
    }
}
