<?php // Indique le début d'un fichier PHP

namespace App\Controller; // Définit l'emplacement virtuel de cette classe dans l'application

// Ces lignes importent d'autres classes nécessaires pour le fonctionnement de ce contrôleur
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Définit la classe CoverLetterController qui étend AbstractController (fournit des fonctionnalités utiles)
class CoverLetterController extends AbstractController
{
    // Déclare des variables privées pour stocker les services nécessaires
    private ConfigGeminiIA $configGeminiIA;
    private Security $security;

    // Le constructeur de la classe, appelé lors de sa création
    public function __construct(ConfigGeminiIA $configGeminiIA, Security $security)
    {
        // Stocke les services fournis dans les variables privées
        $this->configGeminiIA = $configGeminiIA;
        $this->security = $security;
    }

    // Définit une route pour la page principale du générateur de lettre de motivation
    #[Route('/cover/letter', name: 'app_cover_letter')]
    public function index(): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->security->getUser();
        // Affiche la page en utilisant le template 'cover_letter/show.html.twig' et passe l'utilisateur au template
        return $this->render('cover_letter/show.html.twig', ['user' => $user]);
    }

    // Définit une route pour générer la lettre de motivation (accessible uniquement via POST)
    #[Route('/cover/letter/generate', name: 'app_cover_letter_generate', methods: ['POST'])]
    public function generate(Request $request): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->security->getUser();
        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            // Si non connecté, renvoie une erreur JSON avec un code 401 (non autorisé)
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        // Récupère le message supplémentaire envoyé par l'utilisateur dans la requête
        $additionalInfo = $request->request->get('message');
        // Utilise ConfigGeminiIA pour générer la lettre de motivation
        $coverLetter = $this->configGeminiIA->generateCoverLetter($user, $additionalInfo);
        
        // Renvoie la lettre générée au format JSON
        return $this->json(['response' => $coverLetter]);
    }
}

// Résumé du fonctionnement du CoverLetterController :

// 1. But : Ce contrôleur gère les requêtes liées à la génération de lettres de motivation.

// 2. Structure :
//    - Il a deux méthodes principales : index() pour afficher la page et generate() pour créer la lettre.
//    - Il utilise ConfigGeminiIA et Security comme services essentiels.

// 3. Fonctionnement :
//    a. Quand un utilisateur accède à la page principale (/cover/letter) :
//       - La méthode index() est appelée.
//       - Elle récupère l'utilisateur connecté et affiche la page avec ses informations.

//    b. Quand un utilisateur demande de générer une lettre (/cover/letter/generate) :
//       - La méthode generate() est appelée.
//       - Elle vérifie d'abord si l'utilisateur est connecté.
//       - Si oui, elle utilise ConfigGeminiIA pour générer la lettre.
//       - Elle renvoie ensuite la lettre générée au format JSON.

// 4. Sécurité : Le contrôleur vérifie l'authentification de l'utilisateur avant de générer une lettre.

// 5. Interaction avec l'utilisateur : 
//    - Il récupère les informations supplémentaires fournies par l'utilisateur.
//    - Il utilise ces informations avec ConfigGeminiIA pour personnaliser la lettre.

// En résumé, ce contrôleur agit comme un point d'entrée pour les fonctionnalités 
// de génération de lettres de motivation. Il gère les requêtes des utilisateurs, 
// s'assure de leur authentification, et coordonne le processus de génération de lettre 
// en utilisant le service ConfigGeminiIA. Il s'occupe aussi de renvoyer les résultats 
// au format approprié pour que le navigateur de l'utilisateur puisse les afficher.