<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends AbstractController
{
    #[Route('/transactions', name: 'app_transactions')]
    public function index(): Response
    {

        $form = $this->createForm(TransactionFormType::class);

        $transactions = [];

        foreach (range(start: 0, end:  100) as $int) {
            $transaction = new Transaction();
        }

        return $this->render('transactions/index.html.twig', [
            'controller_name' => 'TransactionsController',
            'transactionForm' => $form,
        ]);
    }
}
