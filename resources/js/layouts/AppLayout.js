import React, { useCallback } from 'react';
import { createGlobalStyle } from 'styled-components';
import { Inertia } from '@inertiajs/inertia';
import { usePage, InertiaLink } from '@inertiajs/inertia-react';

const GlobalStyle = createGlobalStyle`
  html,
  body,
  #app {
    height: 100%;
    font-family: "Source Sans Pro";
  }
`;

const AppLayout = ({ children }) => {
  const {
    props: {
      user,
    },
  } = usePage();

  const handleLogout = useCallback(async (e) => {
    e.preventDefault();
    axios.post(route('logout'));
    Inertia.reload();
  }, []);

  return (
    <div className="d-flex flex-column h-100">
      <nav className="navbar navbar-expand-md navbar-light border-bottom">
        <div className="container-fluid">
          <InertiaLink className="navbar-brand" href="/" style={{fontWeight: 'bold'}}>PETSHOP</InertiaLink>
          <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navbar-content">
            <ul className="navbar-nav ms-auto ms-md-3">
            {user && (
                <>
                  <li className="nav-item">
                    <InertiaLink className="nav-link active"  href={route('home')}>Home</InertiaLink>
                  </li>
                  <li className="nav-item dropdown">
                    <a className="nav-link active dropdown-toggle" href="#" 
                    id="menu-paginas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Páginas
                    </a>
                    <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="user-menu">
                      <li>
                        <InertiaLink className="dropdown-item" href="funcionarios">
                          Funcionários
                        </InertiaLink>
                      </li>
                      <li>
                        <InertiaLink className="dropdown-item" href="clientes">
                          Clientes
                        </InertiaLink>
                      </li>
                      <li>
                        <InertiaLink className="dropdown-item" href="servicos">
                          Serviços
                        </InertiaLink>
                      </li>
                      <li>
                        <InertiaLink className="dropdown-item" href="atendimentos">
                          Atendimentos
                        </InertiaLink>
                      </li>
                    </ul>
                  </li>
                </>
              )
            }
            </ul>
            <ul className="navbar-nav ms-auto">
              {!user ? (
                <>
                  <li className="nav-item">
                    <InertiaLink className="nav-link" href={route('login')}>Login</InertiaLink>
                  </li>
                </>
              ) : (
                <>
                  <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" id="user-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      {user.name}
                    </a>
                    <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="user-menu">
                      <li>
                        <InertiaLink className="dropdown-item" href="#" onClick={handleLogout}>
                          Logout
                        </InertiaLink>
                      </li>
                    </ul>
                  </li>
                </>
              )}
            </ul>
          </div>
        </div>
      </nav>
      <main className="flex-fill main">
        <div className="content">
          {children}
        </div>
      </main>
      <GlobalStyle />
    </div>
  );
};

export default AppLayout;