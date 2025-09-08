
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main" style="height: 100vh;">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
        <img src="{{ asset('assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="...">
        <span class="ms-3 font-weight-bold">Inventory V1</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-tachometer-alt text-primary" style="font-size: 1rem;"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Laravel Examples</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('user-profile') ? 'active' : '') }} " href="{{ url('user-profile') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-user-circle text-primary" style="font-size: 1rem;"></i>
            </div>
            <span class="nav-link-text ms-1">User Profile</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('users') ? 'active' : '') }}" href="{{ url('users') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-list-ul ps-2 pe-2 text-center text-dark {{ (Request::is('user-management') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">User Management</span>
        </a>
      </li>

      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Asset Management</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('asset-types*') ? 'active' : '') }}" href="{{ route('asset-types.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-tags ps-2 pe-2 text-center text-dark {{ (Request::is('asset-types*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Asset Types</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('assets*') ? 'active' : '') }}" href="{{ route('assets.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-laptop ps-2 pe-2 text-center text-dark {{ (Request::is('assets*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Assets</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('peripherals*') ? 'active' : '') }}" href="{{ route('peripherals.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-keyboard ps-2 pe-2 text-center text-dark {{ (Request::is('peripherals*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Peripherals</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('asset-transfers*') ? 'active' : '') }}" href="{{ route('asset-transfers.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-exchange-alt ps-2 pe-2 text-center text-dark {{ (Request::is('asset-transfers*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Asset Transfers</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('print-logs*') ? 'active' : '') }}" href="{{ route('print-logs.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-print ps-2 pe-2 text-center text-dark {{ (Request::is('print-logs*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Print Logs</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('audit-trail*') ? 'active' : '') }}" href="{{ route('audit-trail.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-history ps-2 pe-2 text-center text-dark {{ (Request::is('audit-trail*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Audit Trail</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('import-export*') ? 'active' : '') }}" href="{{ route('import.form') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-lg fa-file-import ps-2 pe-2 text-center text-dark {{ (Request::is('import-export*') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Import/Export</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>customer-support</title>
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                  <g transform="translate(1716.000000, 291.000000)">
                    <g transform="translate(1.000000, 0.000000)">
                      <path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
                      <path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
                      <path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">Profile</span>
        </a>
      </li>

    </ul>
  </div>
</aside>
