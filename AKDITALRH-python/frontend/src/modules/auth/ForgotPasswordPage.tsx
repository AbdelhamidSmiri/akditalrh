import { FormEvent, useState } from 'react';
import { Link } from 'react-router-dom';
import { AuthLayout } from '../../components/layout/AuthLayout';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { authApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';

export function ForgotPasswordPage() {
  const [username, setUsername] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);
    try {
      await authApi.forgotPassword(username.trim());
      setSuccess(
        'Si un compte existe pour cet identifiant, un e-mail de réinitialisation a été envoyé.',
      );
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthLayout
      title="Mot de passe oublié"
      subtitle="Indiquez votre identifiant pour recevoir un lien de réinitialisation"
    >
      {error && <Alert variant="danger">{error}</Alert>}
      {success && <Alert variant="success">{success}</Alert>}
      <form onSubmit={onSubmit} className="stack">
        <Input
          label="Identifiant / e-mail"
          name="username"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          required
        />
        <Button type="submit" loading={loading} style={{ width: '100%' }}>
          Envoyer
        </Button>
      </form>
      <p style={{ marginTop: '1.25rem' }}>
        <Link to="/login">Retour à la connexion</Link>
      </p>
    </AuthLayout>
  );
}
