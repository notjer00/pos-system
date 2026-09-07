import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { email } from '@/routes/password';

export default function ForgotPassword({ status }: { status?: string }) {
    return (
        <>
            <Head title="Forgot password" />

            {status && (
                <div className="rounded-lg bg-success/10 p-3 text-center text-sm font-medium text-success">
                    {status}
                </div>
            )}

            <div className="space-y-6">
                <Form {...email.form()}>
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-5">
                                <div className="grid gap-2.5">
                                    <Label htmlFor="email" className="text-sm font-medium">
                                        Email address
                                    </Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        autoComplete="off"
                                        autoFocus
                                        placeholder="you@example.com"
                                        className="h-11"
                                    />
                                    <InputError message={errors.email} />
                                </div>

                                <Button
                                    className="h-11 w-full text-sm font-semibold"
                                    disabled={processing}
                                    data-test="email-password-reset-link-button"
                                >
                                    {processing && (
                                        <LoaderCircle className="h-4 w-4 animate-spin" />
                                    )}
                                    Send reset link
                                </Button>
                            </div>
                        </>
                    )}
                </Form>

                <div className="text-center text-sm text-muted-foreground">
                    Remember your password?{' '}
                    <TextLink href={login()}>Sign in</TextLink>
                </div>
            </div>
        </>
    );
}

ForgotPassword.layout = {
    title: 'Forgot your password?',
    description: 'Enter your email and we will send you a reset link',
};
