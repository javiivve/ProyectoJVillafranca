<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cliente;
use App\Form\FormClienteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ClientesController extends AbstractController
{
    #[Route('/cliente/{id}', name: 'verCliente')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function verCliente($id, EntityManagerInterface $entityManager): Response
    {
        $cliente = $entityManager->getRepository(Cliente::class)->find($id);

        if (!$cliente) {
            throw $this->createNotFoundException('Cliente no encontrado');
        }

        // Obtener las incidencias del cliente actual
        $incidencias = $cliente->getIncidencias();

        return $this->render('Clientes/verCliente.html.twig', [
            'cliente' => $cliente,
            'incidencias' => $incidencias, // Pasar las incidencias
        ]);
    }


    #[Route('/', name: 'verTodosClientes')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $clientes = $entityManager->getRepository(Cliente::class)->findAll();

        return $this->render('Clientes/verTodosClientes.html.twig', [
            'clientes' => $clientes
        ]);
    }



    #[Route('/clientes/add', name: 'addCliente')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function addCliente(EntityManagerInterface $entityManager, Request $request): Response
    {
        
        $cliente =new Cliente();
        
        $formularioCliente = $this->createForm(FormClienteType::class, $cliente);

        $formularioCliente->handleRequest($request);
        if ($formularioCliente->isSubmitted() && $formularioCliente->isValid()) {
            
            $cliente = $formularioCliente->getData();
   
            $entityManager->persist($cliente);
            $entityManager->flush();
            
            $this->addFlash('success', 'El cliente se ha añadido correctamente.');
            return $this->redirectToRoute('verTodosClientes');
           }


        return $this->render('Clientes/addCliente.html.twig', ['formularioCliente'=>$formularioCliente]);

        
    }

    #[Route('/clientes/delete/{id}', name: 'borrarCliente')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function borrarCliente(Cliente $cliente, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($cliente);
        $entityManager->flush();

        $this->addFlash('success', 'El cliente se ha borrado correctamente.');
        return $this->redirectToRoute('verTodosClientes');
        
    }

}
