<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{route('dashboard')}}" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{asset('assets/images/logo-rosalina.png')}}" alt="" class="logo logo-lg"/>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Dashboard</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item {{request()->is('dashboard*')? 'active' : null}}">
                    <a href="{{route('dashboard')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>


                <li class="pc-item pc-caption">
                    <label>Bilheteira:</label>
                    <i class="ti ti-apps"></i>
                </li>
                <li class="pc-item {{request()->is('bilhetes*')? 'active' : null}}">
                    <a href="{{route('bilhetes.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Bilhetes</span>
                    </a>
                </li>

                @role('Admin|Operador')
                <li class="pc-item pc-caption">
                    <label>Trajectos</label>
                    <i class="ti ti-apps"></i>
                </li>
                <li class="pc-item {{request()->is('rotas*')? 'active' : null}}">
                    <a href="{{route('rotas.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Rotas</span>
                    </a>
                </li>
                <li class="pc-item {{request()->is('autocarros*')? 'active' : null}}">
                    <a href="{{route('autocarros.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Autocarros</span>
                    </a>
                </li>
                <li class="pc-item {{request()->is('motoristas*')? 'active' : null}}">
                    <a href="{{route('motoristas.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Motoristas</span>
                    </a>
                </li>
                <li class="pc-item {{request()->is('paradas*')? 'active' : null}}">
                    <a href="{{route('paradas.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Paradas</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Viagens:</label>
                    <i class="ti ti-apps"></i>
                </li>
                <li class="pc-item {{request()->is('viagens*')? 'active' : null}}">
                    <a href="{{route('viagens.index')}}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Viagens</span>
                    </a>
                </li>
                @endrole

                @role('Admin|Operador')
                {{--<li class="pc-item pc-caption">
                    <label>Acessos</label>
                    <i class="ti ti-brand-chrome"></i>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"
                    ><span class="pc-micon"><i class="ti ti-menu"></i></span><span class="pc-mtext">Menu</span
                        ><span class="pc-arrow"><i data-feather="chevron-right"></i></span
                        ></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">Usuários</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">Permissões</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">Grupos</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">Grupos Usuários</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">Grupos Permissões</a></li>
                    </ul>
                </li>--}}
                @endrole
            </ul>

            <div class="w-100 text-center">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>
