import UserCompanyController from '@/actions/App/Http/Controllers/ViewResources/UserCompanyController';
import { AutoCloseAlert } from '@/components/auto-close-alert';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import users from '@/routes/users';
import { BreadcrumbItem, SharedData, User } from '@/types';
import { Transition } from '@headlessui/react';
import { Form, Head, router, usePage } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: users.index().url,
    },
];

export default function ManipulateUserCompany({ users }: { users: User[] | null }) {
    const { auth, currentCompany } = usePage<SharedData>().props;
    const email = useRef<HTMLInputElement>(null);
    const [companyUsers, setCompanyUsers] = useState(users ?? []);
    const [isOpen, setIsOpen] = useState(false);
    const [alertInfo, setAlertInfo] = useState<{
        type: 'success' | 'error';
        message: string;
        subMessage: string;
    }>({
        type: 'success',
        message: '',
        subMessage: '',
    });
    const userCount = companyUsers.length;

    const deleteUser = async (user: User) => {
        try {
            const response = await axios.delete(UserCompanyController.destroy(user.id).url, {
                data: {
                    company_id: currentCompany,
                    user_email: user.email,
                },
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                withCredentials: true,
            });

            if (response.status === 200) {
                setCompanyUsers((currentUsers) => currentUsers.filter((companyUser) => companyUser.id !== user.id));
                router.prefetch(UserCompanyController.index().url, { method: 'get', fresh: true });
                setAlertInfo({
                    type: 'success',
                    message: 'Success',
                    subMessage: 'The user has been removed from this company',
                });
                setIsOpen(true);
            }
        } catch (error) {
            let msg = 'Something went wrong';
            let subMsg = 'Please try again later';

            if (axios.isAxiosError(error) && error.response?.data) {
                msg = error.response.data.message || msg;
                subMsg = error.response.data.errors || subMsg;
            }

            setAlertInfo({
                type: 'error',
                message: msg,
                subMessage: subMsg,
            });
            setIsOpen(true);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User" />

            <ResourcesLayout title="User" description={`${userCount} users in this company`} sidebarNavItems={[]}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall title="User" description="Manage your user" />

                    <div className="space-y-3">
                        {companyUsers.length === 0 ? (
                            <div className="rounded-lg border border-border bg-card p-4 text-sm text-muted-foreground">No users in this company.</div>
                        ) : (
                            companyUsers.map((user) => {
                                const isCurrentUser = user.id === auth?.user?.id;

                                return (
                                    <div
                                        key={user.id}
                                        className="flex flex-col gap-3 rounded-lg border border-border bg-card p-4 transition-colors hover:bg-accent/50 sm:flex-row sm:items-center sm:justify-between"
                                    >
                                        <div className="min-w-0 space-y-1">
                                            <Label className="text-base font-medium text-foreground">{user.name}</Label>
                                            <p className="truncate text-sm text-muted-foreground">{user.email}</p>
                                        </div>

                                        {isCurrentUser ? (
                                            <span className="text-sm text-muted-foreground">Current user</span>
                                        ) : (
                                            <AlertDialog>
                                                <AlertDialogTrigger asChild>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        className="min-w-16 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                    >
                                                        Delete
                                                    </Button>
                                                </AlertDialogTrigger>
                                                <AlertDialogContent>
                                                    <AlertDialogHeader>
                                                        <AlertDialogTitle>Remove user from company?</AlertDialogTitle>
                                                        <AlertDialogDescription>
                                                            This will remove {user.email} from this company. Their account will not be deleted.
                                                        </AlertDialogDescription>
                                                    </AlertDialogHeader>
                                                    <AlertDialogFooter>
                                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                                        <AlertDialogAction className="text-destructive" onClick={() => deleteUser(user)}>
                                                            Delete
                                                        </AlertDialogAction>
                                                    </AlertDialogFooter>
                                                </AlertDialogContent>
                                            </AlertDialog>
                                        )}
                                    </div>
                                );
                            })
                        )}
                    </div>

                    <Form
                        {...UserCompanyController.store.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        onSuccess={() => router.flushAll()}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                <input type="hidden" name="company_id" value={currentCompany ?? ''} />
                                <InputError message={errors.company_id} />
                                <div className="grid gap-2">
                                    <Label htmlFor="user_email">Email</Label>

                                    <Input
                                        id="user_email"
                                        ref={email}
                                        name="user_email"
                                        type="email"
                                        className="mt-1 block w-full"
                                        autoComplete="email"
                                    />

                                    <InputError message={errors.user_email} />
                                </div>
                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save user</Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-neutral-600">Saved</p>
                                    </Transition>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </ResourcesLayout>
            <AutoCloseAlert
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
                title={alertInfo.message}
                description={alertInfo.subMessage}
                autoCloseDelay={3000}
                variant={alertInfo.type}
            />
        </AppLayout>
    );
}
