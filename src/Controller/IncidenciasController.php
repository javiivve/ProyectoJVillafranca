<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cliente;
use App\Entity\Incidencia;
use App\Form\FormIncidenciaType;
use App\Form\FormIncidenciaDosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IncidenciasController extends AbstractController
{
    #[Route('/incidencia/{id}', name: 'verIncidencia')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function verIncidencia($id, EntityManagerInterface $entityManager): Response
    {
        $incidencia = $entityManager->getRepository(Incidencia::class)->find($id);

        return $this->render('Incidencias/verIncidencia.html.twig', [
            'incidencia' => $incidencia, 
        ]);
    }

    
    #[Route('/incidenciaDos/{id}', name: 'verIncidenciaDos')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function verIncidenciaDos($id, EntityManagerInterface $entityManager): Response
    {
        $incidencia = $entityManager->getRepository(Incidencia::class)->find($id);

        return $this->render('Incidencias/verIncidenciaDos.html.twig', [
            'incidencia' => $incidencia, 
        ]);
    }





    #[Route('/incidencias', name: 'verTodasIncidencias')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function verTodasIncidencias(EntityManagerInterface $entityManager): Response
    {
        $incidencias = $entityManager->getRepository(Incidencia::class)->findAll();

        return $this->render('Incidencias/verTodasIncidencias.html.twig', [
            'incidencias' => $incidencias
        ]);
    }



    #[Route('/incidencias/add/{cliente_id}', name: 'addIncidencia')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function addIncidencia(int $cliente_id, EntityManagerInterface $entityManager, Request $request, Security $security): Response
    {
        // Buscar el cliente por su ID
        $cliente = $entityManager->getRepository(Cliente::class)->find($cliente_id);
        
        if (!$cliente) {
            throw $this->createNotFoundException('Cliente no encontrado.');
        }
    
        $incidencia = new Incidencia();
        $incidencia->setCliente($cliente); // Asignar el cliente a la incidencia
    
        // Obtener el usuario actualmente autenticado
        $usuario = $security->getUser();
    
        // Asignar el usuario a la incidencia
        $incidencia->setUsuario($usuario);
        
        $formularioIncidencia = $this->createForm(FormIncidenciaType::class, $incidencia);
        
        $formularioIncidencia->handleRequest($request);
        if ($formularioIncidencia->isSubmitted() && $formularioIncidencia->isValid()) {
            
            $entityManager->persist($incidencia);
            $entityManager->flush();
    
            // Redireccionar a la página de ver cliente pasando el ID del cliente asociado a la incidencia
            return $this->redirectToRoute('verCliente', ['id' => $cliente_id]);
        }
    
        $this->addFlash('success', 'La incidencia se ha añadido correctamente.');
        return $this->render('Incidencias/addIncidencia.html.twig', [
            'formularioIncidencia' => $formularioIncidencia->createView(),
            'cliente' => $cliente, // Pasar el objeto cliente a la plantilla Twig
        ]);
    }


    #[Route('/incidencias/editar/{id}', name: 'editarIncidencia')]
    #[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
    public function editarIncidencia(Incidencia $incidencia, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Obtener el cliente asociado a la incidencia
        $cliente = $incidencia->getCliente();
    
        $formularioIncidencia = $this->createForm(FormIncidenciaType::class, $incidencia);
    
        $formularioIncidencia->handleRequest($request);
        if ($formularioIncidencia->isSubmitted() && $formularioIncidencia->isValid()) {
            $entityManager->flush();
    
            // Redireccionar a la página de ver cliente pasando el ID del cliente asociado a la incidencia
            return $this->redirectToRoute('verCliente', ['id' => $cliente->getId()]);
        }
    
        $this->addFlash('success', 'La incidencia se ha editado correctamente.');
        return $this->render('Incidencias/addIncidencia.html.twig', [
            'formularioIncidencia' => $formularioIncidencia->createView(),
            'incidencia' => $incidencia,
            'cliente' => $cliente, // Pasar la variable cliente a la plantilla Twig
        ]);
    }
    


#[Route('/incidencias/delete/{id}', name: 'borrarIncidencia')]
#[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
public function borrarIncidencia(Incidencia $incidencia, EntityManagerInterface $entityManager): Response
{
    if (!$incidencia) {
        throw $this->createNotFoundException('La incidencia no se encontró.');
    }

    $cliente = $incidencia->getCliente();
    if ($cliente) {
        $clienteId = $cliente->getId();
    } else {
        // Si la incidencia no tiene cliente asociado, redirige a una página predeterminada o maneja el caso según tu lógica
        return $this->redirectToRoute('inicio');
    }

    $entityManager->remove($incidencia);
    $entityManager->flush();

    $this->addFlash('success', 'La incidencia se ha eliminado correctamente.');
    // Redirigir a la página de ver cliente pasando el ID del cliente asociado a la incidencia
    return $this->redirectToRoute('verCliente', ['id' => $clienteId]);
}   


#[Route('/incidenciasDos/add/', name: 'addIncidenciaDos')]
#[IsGranted('ROLE_USER', message: 'Debe de iniciar sesion')]
public function addIncidenciaDos(EntityManagerInterface $entityManager, Request $request, Security $security): Response
{
    $incidencia = new Incidencia();

    // Obtener el usuario actualmente autenticado
    $usuario = $security->getUser();
    $incidencia->setUsuario($usuario);

    $formularioIncidenciaDos = $this->createForm(FormIncidenciaDosType::class, $incidencia);
        
            $formularioIncidenciaDos->handleRequest($request);
            if ($formularioIncidenciaDos->isSubmitted() && $formularioIncidenciaDos->isValid()) {
                
                $entityManager->persist($incidencia);
                $entityManager->flush();

                // Redireccionar a la página de ver cliente pasando el ID del cliente asociado a la incidencia
                return $this->redirectToRoute('verTodasIncidencias');
        
            }
        
            return $this->render('Incidencias/addIncidenciaDos.html.twig', [
                'formularioIncidenciaDos' => $formularioIncidenciaDos->createView(),
            ]);
}

}
