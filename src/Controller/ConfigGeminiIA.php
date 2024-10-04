<?php // Indique le début d'un fichier PHP

namespace App\Controller; // Définit l'emplacement virtuel de cette classe dans l'application

// Ces lignes importent d'autres classes nécessaires pour le fonctionnement de ce code
use App\Entity\User;
use Gemini\Client as GeminiClient;
use Gemini\Data\GenerationConfig;
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\HarmBlockThreshold;

// Définit la classe ConfigGeminiIA
class ConfigGeminiIA
{
    // Déclare une variable privée pour stocker le client Gemini
    private GeminiClient $gemini;

    // Le constructeur de la classe, appelé lors de sa création
    public function __construct(GeminiClient $gemini)
    {
        // Stocke le client Gemini fourni dans la variable privée
        $this->gemini = $gemini;
    }

    // Méthode principale pour générer une lettre de motivation
    public function generateCoverLetter(User $user, string $additionalInfo): string
    {
        // Crée la configuration pour la génération de texte
        $generationConfig = $this->createGenerationConfig();
        // Crée le texte d'instruction (prompt) pour l'IA
        $prompt = $this->createPrompt($user, $additionalInfo);

        // Utilise le client Gemini pour générer le contenu
        $result = $this->gemini->geminiPro()
            ->withGenerationConfig($generationConfig) // Applique la configuration
            ->withSafetySetting($this->createSafetySetting(HarmCategory::HARM_CATEGORY_HATE_SPEECH)) // Ajoute un filtre contre les discours haineux
            ->withSafetySetting($this->createSafetySetting(HarmCategory::HARM_CATEGORY_DANGEROUS_CONTENT)) // Ajoute un filtre contre le contenu dangereux
            ->generateContent($prompt); // Génère le contenu basé sur le prompt

        // Retourne le texte généré
        return $result->text();
    }

    // Méthode privée pour créer la configuration de génération
    private function createGenerationConfig(): GenerationConfig
    {
        // Retourne une nouvelle configuration avec des paramètres spécifiques
        return new GenerationConfig(
            temperature: 0.7, // Contrôle la créativité (0.0 = très conservateur, 1.0 = très créatif)
            topK: 40, // Limite le nombre de mots possibles à chaque étape
            topP: 0.95, // Autre façon de limiter les choix de mots
            maxOutputTokens: 1024 // Limite la longueur du texte généré
        );
    }

    // Méthode privée pour créer le texte d'instruction (prompt)
    private function createPrompt(User $user, string $additionalInfo): string
    {
        // Retourne une chaîne de caractères formatée avec les informations de l'utilisateur
        return "Génère une lettre de motivation professionnelle pour un stage de Concepteur Développeur d'Applications de 3 mois. Utilise les informations suivantes du candidat :
                Nom : {$user->getLastName()}
                Prénom : {$user->getFirstName()}
                Email : {$user->getEmail()}
                Informations supplémentaires : {$additionalInfo}

                La lettre doit inclure :
                1. Une introduction accrocheuse
                2. Les compétences pertinentes du candidat
                3. La motivation pour le stage
                4. Une conclusion convaincante

                Voici la lettre de motivation :";
    }

    // Méthode privée pour créer un paramètre de sécurité
    private function createSafetySetting(HarmCategory $category): SafetySetting
    {
        // Retourne un nouveau paramètre de sécurité
        return new SafetySetting(
            category: $category, // Catégorie de contenu à filtrer
            threshold: HarmBlockThreshold::BLOCK_MEDIUM_AND_ABOVE // Niveau de filtrage (bloque le contenu modérément inapproprié et plus)
        );
    }
}

// Résumé du fonctionnement de la classe ConfigGeminiIA :

// 1. But : Cette classe est responsable de configurer et d'utiliser l'IA Gemini pour générer des lettres de motivation personnalisées.

// 2. Structure :
//    - Elle a une méthode principale (generateCoverLetter) qui orchestre tout le processus.
//    - Plusieurs méthodes privées (createGenerationConfig, createPrompt, createSafetySetting) préparent les différents éléments nécessaires.

// 3. Fonctionnement :
//    a. Lors de la création de la classe, elle reçoit un client Gemini.
//    b. Quand on demande de générer une lettre (generateCoverLetter) :
//       - Elle prépare la configuration pour contrôler comment l'IA génère le texte.
//       - Elle crée un texte d'instruction (prompt) avec les informations de l'utilisateur.
//       - Elle configure des filtres de sécurité pour éviter le contenu inapproprié.
//       - Elle demande à l'IA de générer la lettre et retourne le résultat.

// 4. Sécurité : La classe met en place des filtres contre les discours haineux et le contenu dangereux.

// 5. Personnalisation : Le prompt inclut des détails spécifiques à l'utilisateur pour une lettre sur mesure.

// En résumé, cette classe agit comme un pont entre l'application et l'IA Gemini, 
// en s'assurant que l'IA reçoit les bonnes instructions et configurations pour générer 
// une lettre de motivation professionnelle et sûre, adaptée à chaque utilisateur.