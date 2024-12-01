<?php

namespace App\Controller;

use App\Entity\ShopItemsInventory;
use App\Form\ShopItemsInventoryType;
use App\Repository\ShopItemsInventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop/items/inventory')]
final class ShopItemsInventoryController extends AbstractController
{
    #[Route(name: 'app_shop_items_inventory_index', methods: ['GET'])]
    public function index(ShopItemsInventoryRepository $shopItemsInventoryRepository): Response
    {
        return $this->render('shop_items_inventory/index.html.twig', [
            'shop_items_inventories' => $shopItemsInventoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shop_items_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shopItemsInventory = new ShopItemsInventory();
        $form = $this->createForm(ShopItemsInventoryType::class, $shopItemsInventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shopItemsInventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_items_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop_items_inventory/new.html.twig', [
            'shop_items_inventory' => $shopItemsInventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_items_inventory_show', methods: ['GET'])]
    public function show(ShopItemsInventory $shopItemsInventory): Response
    {
        return $this->render('shop_items_inventory/show.html.twig', [
            'shop_items_inventory' => $shopItemsInventory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shop_items_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShopItemsInventory $shopItemsInventory, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Ensure only admins can access this
        
        $form = $this->createForm(ShopItemsInventoryType::class, $shopItemsInventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_items_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop_items_inventory/edit.html.twig', [
            'shop_items_inventory' => $shopItemsInventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_items_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, ShopItemsInventory $shopItemsInventory, EntityManagerInterface $entityManager): Response
    {

          $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Ensure only admins can access this

        if ($this->isCsrfTokenValid('delete'.$shopItemsInventory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shopItemsInventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shop_items_inventory_index', [], Response::HTTP_SEE_OTHER);
    }
}
