import CompanyController from '@/actions/App/Http/Controllers/CompanyController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import { type BreadcrumbItem } from '@/types';
import { CompanyNavItems, type companyModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head } from '@inertiajs/react';
import { useRef } from 'react';
import company from '@/routes/company';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: company.index().url
    },
];

export default function Company(company: companyModel | null) {
    const name = useRef<HTMLInputElement>(null);
    const description = useRef<HTMLTextAreaElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={company !== null ? 'Edit a company' : 'Create a company'} />

            <ResourcesLayout title="Company" description="Manage your company information" sidebarNavItems={CompanyNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall title={company !== null ? 'Edit a company' : 'Create a company'} description='Edit the information of your company' />

                    <Form
                        {...CompanyController.update.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['password', 'password_confirmation', 'current_password']}
                        resetOnSuccess
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="current_password">Name</Label>

                                    <Input id="name" ref={name} name="name" type="text" className="mt-1 block w-full" autoComplete="name" />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="description">Description</Label>

                                    <Textarea
                                        id="description"
                                        ref={description}
                                        name="description"
                                        className="mt-1 block w-full"
                                        autoComplete="description"
                                        placeholder="Company's Description"
                                    />

                                    <InputError message={errors.description} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save company</Button>

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
        </AppLayout>
    );
}
