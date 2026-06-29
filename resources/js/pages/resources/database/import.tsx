import DatabaseSidebarSelect from "@/components/database-sidebar-select";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import database from "@/routes/database";
import { BreadcrumbItem, SharedData } from "@/types";
import { databaseModel, DatabaseNavItems } from "@/types/resources.d";
import { Form, Head, router, usePage } from "@inertiajs/react";
import {
    Field,
    FieldDescription,
    FieldLabel,
} from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import { Transition } from "@headlessui/react";
import { Button } from "@/components/ui/button";
import ImportDatabaseController from "@/actions/App/Http/Controllers/ImportDatabaseController";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Database',
        href: database.index().url,
    },
];

export default function Import({ databases }: { databases: databaseModel[] }) {
    const { currentDatabase } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Import Database' />

            <ResourcesLayout
                title='Import Database'
                description='Import a database'
                sidebarNavItems={DatabaseNavItems}
                sidebarExtraNavItems={DatabaseSidebarSelect(databases)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall title='Import Database' description='Import a database' />

                    <Form
                        { ...ImportDatabaseController.store.form() }
                        options={{
                            preserveScroll: true
                        }}
                        onSuccess={() => router.flushAll()}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                <input type="hidden" name="database_id" value={currentDatabase ?? ''} />
                                <InputError message={errors.database_id} />

                                <Field>
                                    <FieldLabel htmlFor="file">SQL File</FieldLabel>
                                    <Input id="file" name="file" type="file" accept=".sql" />
                                    <FieldDescription>Select a SQL file to upload.</FieldDescription>

                                    <InputError message={errors.file} />
                                </Field>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Send</Button>

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
    )
}