import TableController from '@/actions/App/Http/Controllers/ViewResources/TableController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import DatabaseSidebarSelect from '@/components/database-sidebar-select';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import table from '@/routes/table';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { TableNavItems, tableModel, databaseModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Table',
        href: table.index().url,
    },
];

export default function ManipulateTable({
    table,
    databases
}: {
    table: tableModel[] | null;
    databases: databaseModel[];
}) {
    const { currentDatabase } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={table !== null ? 'Edit a table' : 'Create a table'} />

            <ResourcesLayout
                title="Table"
                description="Manage your table information"
                sidebarNavItems={TableNavItems}
                sidebarExtraNavItems={DatabaseSidebarSelect(databases)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={table !== null ? 'Edit a table' : 'Create a table'}
                        description="Edit the information of your table"
                    />

                    <Form
                        {...(table !== null
                            ? TableController.update.form({ table: table[0]?.id })
                            : TableController.store.form()
                        )}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {table !== null ? <input type="hidden" name="id" value={table[0]?.id} /> : null}
                                <input type="hidden" name="database_id" value={currentDatabase ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={ table !== null ? table[0]?.name : undefined }
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save table</Button>

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
