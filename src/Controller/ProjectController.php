<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Project;
use App\Form\ProjectType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }
    
    /**
     * @Route("/", name="project_index", methods={"GET"})
     * @param ProjectRepository $projectRepository
     *
     * @return Response
     */
    public function index(ProjectRepository $projectRepository)
    {
        
        $projects = $projectRepository->totalEmployees();
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function new(EntityManagerInterface $em, Request $request) : Response
    {
        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /**@var Project $project */
            $project = $form->getData();
            $em->persist($project);
            $em->flush();
            
            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig',[
            'projectForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Project $project, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_index');
        }
        return $this->render('project/edit.html.twig',[
            'projectForm' => $form->createView(),
            'id' => $project->getId(),
        ]);
    }
    /**
     * @Route("/delete", name="project_delete", methods={"post"})
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $em, ProjectRepository $projectRepository)
    {
        $token = new CsrfToken('authenticate', $request->request->get('_csrf_token'));
        if (!$this->csrfTokenManager->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        } else {
            $project = $projectRepository->find($request->request->get('project_id'));
           
            $em->remove($project);
            $em->flush();

            
            return $this->redirectToRoute('project_index');
        }
    }


}
