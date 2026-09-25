import type { ButtonHTMLAttributes, ReactNode } from 'react';

type Variant = 'primary' | 'secondary' | 'success' | 'danger' | 'orange' | 'ghost' | 'teal';

interface Props extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: Variant;
  loading?: boolean;
  size?: 'sm' | 'md' | 'lg';
  children: ReactNode;
}

const VARIANT_CLASS: Record<Variant, string> = {
  primary: 'btn btn-primary-rounded2',
  secondary: 'btn btn-secondary-rounded',
  success: 'btn btn-success-rounded',
  danger: 'btn btn-primary-rounded',
  orange: 'btn btn-orange-rounded',
  ghost: 'btn btn-secondary-rounded',
  teal: 'btn btn-primary-rounded2',
};

export function Button({
  variant = 'primary',
  loading,
  size,
  children,
  className = '',
  disabled,
  ...rest
}: Props) {
  void size;
  return (
    <button
      className={`${VARIANT_CLASS[variant]} ${className}`.trim()}
      disabled={disabled || loading}
      {...rest}
    >
      {loading ? '…' : children}
    </button>
  );
}
