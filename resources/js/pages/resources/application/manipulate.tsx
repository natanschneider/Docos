import ApplicationController from '@/actions/App/Http/Controllers/ViewResources/ApplicationController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import ProjectSidebarSelect from '@/components/project-sidebar-select';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import application from '@/routes/application';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { ApplicationNavItems, databaseModel, projectModel, type applicationModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import { ChevronsUpDown } from 'lucide-react';
import React, { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Application',
        href: application.index().url,
    },
];

export default function ManipulateApplication({
    application,
    databases,
    projects,
}: {
    application: applicationModel[] | null;
    databases: databaseModel[];
    projects: projectModel[];
}) {
    const { currentProject } = usePage<SharedData>().props;
    const [isOpen, setIsOpen] = React.useState(true);
    const name = useRef<HTMLInputElement>(null);
    const [items, setItems] = React.useState<string[]>(application !== null ? application[0]?.databases.map((db) => db.id.toString()) : []);
    const [selected, setSelected] = React.useState<string | undefined>(undefined);

    const databaseArr = databases.reduce(
        (acc, database) => {
            acc[database.id] = database.name;
            return acc;
        },
        {} as Record<string, string>,
    );

    const addItem = (item: string) => {
        if (!items.includes(item)) {
            setItems([...items, item]);
        }

        setSelected('');
    };

    const removeItem = (item: string) => {
        setItems(items.filter((i) => i !== item));
        setSelected('');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={application !== null ? 'Edit a application' : 'Create a application'} />

            <ResourcesLayout
                title="Application"
                description="Manage your application information"
                sidebarNavItems={ApplicationNavItems}
                sidebarExtraNavItems={ProjectSidebarSelect(projects)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={application !== null ? 'Edit a application' : 'Create a application'}
                        description="Edit the information of your application"
                    />

                    <Form
                        {...(application !== null
                            ? ApplicationController.update.form({
                                  application: application[0]?.id,
                              })
                            : ApplicationController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {application !== null ? <input type="hidden" name="id" value={application[0]?.id} /> : null}
                                <input type="hidden" name="project_id" value={currentProject ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={application !== null ? application[0]?.name : undefined}
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Collapsible open={isOpen} onOpenChange={setIsOpen} className="flex w-[350px] flex-col gap-2">
                                        <div className="flex items-center justify-between gap-4 px-4">
                                            <Label htmlFor="database_id">Databases</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant="ghost" size="icon" className="size-8">
                                                    <ChevronsUpDown />
                                                    <span className="sr-only">Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selected} name="database_id" onValueChange={(value) => addItem(value)}>
                                            <SelectTrigger className="w-[180px]">
                                                <SelectValue placeholder="Select an database" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Databases</SelectLabel>
                                                    {databases.map((database) => (
                                                        <SelectItem key={database.id} value={database.id.toString()}>
                                                            {database.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <InputError message={errors.databases} />

                                        <input type="hidden" name="databases[]" value={items} />
                                        <CollapsibleContent className="mt-3 flex flex-col gap-2">
                                            {items.map((item, index) => (
                                                <div className="flex items-center rounded-md border px-4 py-2 font-mono text-sm" key={index}>
                                                    <p className="grow">{databaseArr[item]}</p>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                        onClick={() => removeItem(item)}
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            ))}
                                        </CollapsibleContent>
                                    </Collapsible>
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save application</Button>

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
