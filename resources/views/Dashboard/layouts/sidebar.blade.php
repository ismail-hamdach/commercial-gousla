<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-5" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>

                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master')|| Auth::user()->hasRole('employee'))
                    <li class="nav-item ">
                        <a class="nav-link " href="{{ route('dashboard') }}">
                            <i class="fas fa-shopping-bag"></i>Table de bord <span class="badge badge-success">6</span>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master') )

                        <li class="nav-item ">
                            <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                                data-target="#submenu-3 " aria-controls="submenu-3"><i
                                    class="fas fa-shopping-bag"></i>Gestion des produits <span
                                    class="badge badge-success">6</span>
                            </a>

                            <div id="submenu-3" class="collapse submenu" style="">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('products.index') }}">Tout les produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('products.create') }}">Ajouter un produit</a>
                                    </li>
                                </ul>
                            </div>

                        </li>
                        @endif
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master') )

                        <li class="nav-item ">
                            <a class="nav-link " href="{{ route('companys.index') }}"><i
                                    class="fas fa-building"></i>Paramètres de l'entreprise <span
                                    class="badge badge-success">6</span></a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                                data-target="#submenu-7" aria-controls="submenu-7"><i class="fas fa-user"></i>Gestion
                                des
                                utilisateurs <span class="badge badge-success">6</span></a>
                            <div id="submenu-7" class="collapse submenu" style="">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('users.index') }}">Tout les utilisateurs</a>
                                    </li>
                                    @if (Auth::user()->hasRole('master'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('users.index') }}">Ajouter utilisateurs</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master') || Auth::user()->hasRole('gerant product')  || Auth::user()->hasRole('gerant Validation BL')  || Auth::user()->hasRole('gerant BL') )
                        <li class="nav-item ">
                            <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                                data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-address-book"></i>Gestion des commerciaux<span class="badge badge-success">6</span></a>
                            <div id="submenu-1" class="collapse submenu" style="">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master') )
                                        <a class="nav-link" href="{{ route('employees.statistics') }}">Statistique</a>
                                        <a class="nav-link" href="{{ route('employees.clients') }}">Liste des clients</a>
                                        <a class="nav-link" href="{{ route('clients.check') }}">check clients</a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master')  || Auth::user()->hasRole('gerant BL') || Auth::user()->hasRole('gerant Validation BL')  )
                                        <a class="nav-link" href="{{ route('employees.allorders') }}">Bons des commandes</a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master')  || Auth::user()->hasRole('gerant product') )
                                        <a class="nav-link" href="{{ route('employees.products') }}">Liste des produits</a>
                                        <a class="nav-link" href="{{ route('employeescategories.index') }}">Liste des categories</a>
                                        <a class="nav-link" href="{{ route('employeesstocks.index') }}">Liste des depots</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </li>

                    @endif
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master')  || Auth::user()->hasRole('employee') )

                    <li class="nav-item ">
                        <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                            data-target="#submenu-2" aria-controls="submenu-2"><i class="fas fa-users"></i>Gestion des
                            clients <span class="badge badge-success">6</span></a>
                        <div id="submenu-2" class="collapse submenu" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('clients.index') }}">Tout les clients</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('clients.create') }}">Ajouter client</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master|employee|gerant BL|gerant Validation BL') )


                    <li class="nav-item ">
                        <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                            data-target="#submenu-5" aria-controls="submenu-5"><i
                                class="fas fa-shopping-cart"></i>Gestion des commande <span
                                class="badge badge-success">6</span></a>
                        <div id="submenu-5" class="collapse submenu" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.index') }}">Tout les commands</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.create') }}">Ajouter command</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                     @endif
                    @if (Auth::user()->hasRole('master'))
                    <li class="nav-item ">
                        <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                            data-target="#submenu-6" aria-controls="submenu-6"><i class="fa fa-address-card"></i>Gestion
                            des roles<span class="badge badge-success">6</span></a>
                        <div id="submenu-6" class="collapse submenu" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.index') }}">Tout les roles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.create') }}">Ajouter roles</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                   @if (Auth::user()->hasRole('admin|gerant rapport|master'))
                    <li class="nav-item ">
                        <a class="nav-link " href="#" data-toggle="collapse" aria-expanded="false"
                            data-target="#submenu-8" aria-controls="submenu-1"><i class="fas fa-money-bill-alt"></i>Statistiques<span class="badge badge-success">6</span></a>
                        <div id="submenu-8" class="collapse submenu" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('repport') }}">Rapport</a>
                                    @if(auth()->user()->hasRole('admin|master'))
                                    <a class="nav-link" href="{{ route('statistics.board') }}">Board</a>
                                    <a class="nav-link" href="{{ route('statistics.clientclassement') }}">Classement</a>
                                    <a class="nav-link" href="{{ route('deleted-orders') }}">Commandes Supprimé</a>
                                    <a class="nav-link" href="{{ route('trackings-orders') }}">Historique</a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
