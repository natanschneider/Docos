import DatabaseController from '@/actions/App/Http/Controllers/ViewResources/DatabaseController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import database from '@/routes/database';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { DatabaseNavItems, type databaseModel, type engineModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, router, usePage } from '@inertiajs/react';
import { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Database',
        href: database.index().url,
    },
];

export default function ManipulateDatabase({ database, engines }: { database: databaseModel | null; engines: engineModel[] }) {
    const { currentCompany } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={database !== null ? 'Edit a database' : 'Create a database'} />

            <ResourcesLayout title="Database" description="Manage your database information" sidebarNavItems={DatabaseNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={database !== null ? 'Edit a database' : 'Create a database'}
                        description="Edit the information of your database"
                    />

                    <Form
                        {...(database !== null
                            ? DatabaseController.update.form({
                                  database: database?.id,
                              })
                            : DatabaseController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        onSuccess={() => router.flushAll()}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {database !== null ? <input type="hidden" name="id" value={database?.id} /> : null}
                                <input type="hidden" name="company_id" value={currentCompany ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={database?.name}
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="engine">Engine</Label>

                                    <Select name="engine_id" required defaultValue={database?.engine_id.toString()}>
                                        <SelectTrigger className="w-[180px]">
                                            <SelectValue placeholder="Select an engine" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectLabel>Engines</SelectLabel>
                                                {engines.map((engine) => (
                                                    <SelectItem key={engine.id} value={engine.id.toString()}>
                                                        {engine.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>

                                    <InputError message={errors.engine} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save database</Button>

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
