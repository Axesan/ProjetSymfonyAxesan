<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard as HtmlDashboard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardController extends AbstractDashboardController
{
    // #[Route('/login-admin', name: 'login-admin')]

    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->render('@EasyAdmin/page/login.html.twig', [
    //         // parameters usually defined in Symfony login forms
    //         'error' => $error,
    //         'last_username' => $lastUsername,

    //         // OPTIONAL parameters to customize the login form:

    //         // the translation_domain to use (define this option only if you are
    //         // rendering the login template in a regular Symfony controller; when
    //         // rendering it from an EasyAdmin Dashboard this is automatically set to
    //         // the same domain as the rest of the Dashboard)
    //         'translation_domain' => 'admin',

    //         // the title visible above the login form (define this option only if you are
    //         // rendering the login template in a regular Symfony controller; when rendering
    //         // it from an EasyAdmin Dashboard this is automatically set as the Dashboard title)
    //         'page_title' => 'ACME login',

    //         // the string used to generate the CSRF token. If you don't define
    //         // this parameter, the login form won't include a CSRF token
    //         'csrf_token_intention' => 'authenticate',

    //         // the URL users are redirected to after the login (default: '/admin')
    //         'target_path' => $this->generateUrl('admin'),

    //         // the label displayed for the username form field (the |trans filter is applied to it)
    //         'username_label' => 'Your username',

    //         // the label displayed for the password form field (the |trans filter is applied to it)
    //         'password_label' => 'Your password',

    //         // the label displayed for the Sign In form button (the |trans filter is applied to it)
    //         'sign_in_label' => 'Log in',

    //         // the 'name' HTML attribute of the <input> used for the username field (default: '_username')
    //         'username_parameter' => 'my_custom_username_field',

    //         // the 'name' HTML attribute of the <input> used for the password field (default: '_password')
    //         'password_parameter' => 'my_custom_password_field',

    //         // whether to enable or not the "forgot password?" link (default: false)
    //         'forgot_password_enabled' => false,

    //         // le chemin (c'est-à-dire une URL relative ou absolue) à visiter lorsque l'on clique sur le lien "mot de passe oublié ?" (par défaut : '#')
    //         // 'forgot_password_path' => $this->generateUrl('#'),

    //         // the label displayed for the "forgot password?" link (the |trans filter is applied to it)
    //         'forgot_password_label' => 'Forgot your password?',

    //         // whether to enable or not the "remember me" checkbox (default: false)
    //         'remember_me_enabled' => false,

    //         // remember me name form field (default: '_remember_me')
    //         'remember_me_parameter' => '_remember_me',

    //         // whether to check by default the "remember me" checkbox (default: false)
    //         'remember_me_checked' => true,

    //         // the label displayed for the remember me checkbox (the |trans filter is applied to it)
    //         'remember_me_label' => 'Remember me',
    //     ]);
    // }

    #[Route('/admin', name: 'admin')]

    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        //  return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
           // the name visible to end users
           ->setTitle('ACME Corp.')
           // you can include HTML contents too (e.g. to link to an image)
           ->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

           // the path defined in this method is passed to the Twig asset() function
           ->setFaviconPath('favicon.svg')

           // the domain used by default is 'messages'
           ->setTranslationDomain('my-custom-domain')

           // there's no need to define the "text direction" explicitly because
           // its default value is inferred dynamically from the user locale
           ->setTextDirection('ltr')

           // set this option if you prefer the page content to span the entire
           // browser width, instead of the default design which sets a max width
           ->renderContentMaximized()

           // set this option if you prefer the sidebar (which contains the main menu)
           // to be displayed as a narrow column instead of the default expanded design
           ->renderSidebarMinimized()

           // by default, all backend URLs include a signature hash. If a user changes any
           // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
           // triggers an error. If this causes any issue in your backend, call this method
           // to disable this feature and remove all URL signature checks
           ->disableUrlSignatures()

           // by default, all backend URLs are generated as absolute URLs. If you
           // need to generate relative URLs instead, call this method
           ->generateRelativeUrls();

    }

        public function configureMenuItems(): iterable
        {   
            return [
                 
                
                 /**
                  * On ajoute nos "entity" qui sont "User" qui permet de gerer mais utilisateurs
                  * ainsi que "Category" && "Product"
                  */
                // Section Users 
            
                 MenuItem::linkToCrud('Utilisateur', 'fa fa-user', User::class),
                 MenuItem::linkToCrud('Commande', 'fa fa-shopping-cart', Order::class),
                 //Section catégorie
                 MenuItem::linkToCrud('Catégorie', 'fa fa-list-ul', Category::class),
                 MenuItem::linkToCrud('Produit ', 'fa fa-shopping-basket', Product::class),
                 MenuItem::linkToCrud('Transporteur', 'fa fa-truck', Carrier::class),
                 MenuItem::linkToCrud('Contact', 'far fa-envelope', Contact::class),
                 MenuItem::linkToCrud('Caroussel', 'fas fa-paint-brush', Header::class),

                 MenuItem::linkToLogout('Se déconnecter', 'fa fa-chevron-left text-danger'),
            ];
            
        }
        
        
}
