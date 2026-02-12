import ColumnController from '@/actions/App/Http/Controllers/ViewResources/ColumnController';
import ColumnSidebarSelect from '@/components/column-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import column from '@/routes/column';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { columnModel, ColumnNavItems, tableModel, databaseModel, typeModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Column',
        href: column.index().url,
    },
];

export default function ManipulateColumn({
    column,
    tables,
    databases,
    primaryKey,
    foreignKey,
    types
}: {
    column: columnModel[];
    tables: tableModel[];
    databases: databaseModel[];
    primaryKey: columnModel[];
    foreignKey: columnModel[];
    types: typeModel[];
}) {
    const { currentTable } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={column !== null ? 'Edit a column' : 'Create a column'} />

            <ResourcesLayout
                title="Column"
                description="Manage your column information"
                sidebarNavItems={ColumnNavItems}
                sidebarExtraNavItems={ColumnSidebarSelect({databases, tables})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={column !== null ? 'Edit a column' : 'Create a column'}
                        description="Edit the information of your column"
                    />

                    <Form
                        {...(column !== null
                            ? ColumnController.update.form({
                                  column: column[0]?.id,
                              })
                            : ColumnController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {column !== null ? <input type="hidden" name="id" value={column[0]?.id} /> : null}
                                <input type="hidden" name="table_id" value={currentTable ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={ column !== null ? column[0]?.name : undefined }
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save column</Button>

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
