import { Outlet } from 'react-router-dom';
import { useState } from 'react';
import { Sidebar } from './Sidebar';

export function AppShell() {
  const [mobileOpen, setMobileOpen] = useState(false);

  return (
    <>
      <Sidebar mobileOpen={mobileOpen} onClose={() => setMobileOpen(false)} />
      <div className="main-content">
        <div id="container">
          <div id="content">
            <Outlet />
          </div>
        </div>
      </div>
      <div className="topmenu_mobile">
        <button
          type="button"
          className="btn btn-menu d-md-none"
          id="sidebarToggle"
          style={{ position: 'fixed', top: '1rem', left: '1rem', zIndex: 1001 }}
          onClick={() => setMobileOpen((v) => !v)}
          aria-label="Menu"
        >
          <i className="fas fa-bars" />
        </button>
      </div>
      {mobileOpen && (
        <button
          type="button"
          className="app-overlay d-md-none"
          aria-label="Fermer"
          onClick={() => setMobileOpen(false)}
          style={{
            position: 'fixed',
            inset: 0,
            background: 'rgba(0,0,0,0.35)',
            zIndex: 999,
            border: 0,
          }}
        />
      )}
    </>
  );
}
