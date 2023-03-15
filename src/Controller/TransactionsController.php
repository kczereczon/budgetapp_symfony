<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends AbstractController
{
    #[Route('/transactions', name: 'app_transactions')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(TransactionFormType::class);

        $transactions = $entityManager->getRepository(Transaction::class)->findBy(criteria: [], limit: 25);

        return $this->render('transactions/index.html.twig', [
            'controller_name' => 'TransactionsController',
            'transactionForm' => $form,
            'transactions' => $transactions
        ]);
    }
}
