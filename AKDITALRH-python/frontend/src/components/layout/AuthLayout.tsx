import type { ReactNode } from 'react';
import './AuthLayout.css';

interface Props {
  title: string;
  subtitle?: string;
  children: ReactNode;
}

export function AuthLayout({ title, subtitle, children }: Props) {
  return (
    <div className="container-fluid login-page p-0 m-0">
      <div className="row m-0 p-0 h-100">
        <div className="col-md-6 col-sm-12 p-0 m-0 d-none d-md-block">
          <div className="image-bg">
            <div className="text-box">
              <h1 className="text-white poppins-bold fs-1 mb-5">
                Bienvenue sur la plateforme de gestion des moyens généraux
                Akdital
              </h1>
            </div>
          </div>
        </div>
        <div className="col-md-6 col-sm-12 p-0 m-0">
          <div className="login-form-panel">
            <div className="mb-5">
              <img src="/img/svg/logo_login.svg" alt="Akdital" width={281} height={73} />
            </div>
            <h2 className="poppins-bold fs-1 mb-4 text-color-primary">{title}</h2>
            {subtitle && <p className="login-slogan">{subtitle}</p>}
            {children}
          </div>
        </div>
      </div>
    </div>
  );
}
