import EndpointController from '@/actions/App/Http/Controllers/ViewResources/EndpointController';
import ApplicationSidebarSelect from '@/components/application-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import endpoint from '@/routes/endpoint';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { columnModel, projectModel, endpointModel, EndpointNavItems, tableModel, type applicationModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import { ChevronsUpDown } from 'lucide-react';
import React, { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Endpoint',
        href: endpoint.index().url,
    },
];

export default function ManipulateEndpoint({
    endpoint,
    tables,
    projects,
    applications,
}: {
    endpoint: endpointModel[] | null;
    tables: tableModel[];
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const { currentApplication } = usePage<SharedData>().props;
    const [isOpen, setIsOpen] = React.useState(true);
    const name = useRef<HTMLInputElement>(null);
    const [items, setItems] = React.useState<string[]>(() => {
        if (!endpoint || !endpoint[0]?.columns) return [];

        const uniqueItems = Array.from(new Set(
            endpoint[0].columns
            .map((col) => col.id.toString())
        ));
        console.log(uniqueItems);
        return uniqueItems;
    });
    const [selectedTables, setSelectedTables] = React.useState<string[]>(() => {
        if (!endpoint || !endpoint[0]?.columns) return [];

        const uniqueItems = Array.from(new Set(
            endpoint[0].columns
            .map((col) => col.table_id.toString())
        ));

        return uniqueItems;
    });
    const [selected, setSelected] = React.useState<string | undefined>(undefined);

    const tableArr = tables.reduce(
        (acc, tables) => {
            acc[tables.id] = tables;
            return acc;
        },
        {} as Record<string, tableModel>,
    );

    const columnArr = tables.reduce((acc, table) => {
        const columns = table?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = column;
        });
        return acc;
    }, {} as Record<string, columnModel>);

    const columnTable = tables.reduce((acc, table) => {
        const columns = table?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = table?.id?.toString();
        });
        return acc;
    }, {} as Record<string, string>);

    const addItem = (item: string) => {
        if (!items?.includes(item)) {
            setItems([...items, item]);
            if (! selectedTables?.includes(columnTable[item])) {
                setSelectedTables([...selectedTables??[], columnTable[item]]);
            }
        }

        setSelected(undefined);
    };

    const addTable = (item: string) => {
        if (! selectedTables?.includes(item)) {
            setSelectedTables([...selectedTables??[], item]);
        }
    };

    const removeItem = (item: string) => {
        setItems(items?.filter((i) => i !== item));
        setSelected(undefined);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={endpoint !== null ? 'Edit a endpoint' : 'Create a endpoint'} />

            <ResourcesLayout
                title="Endpoint"
                description="Manage your endpoint information"
                sidebarNavItems={EndpointNavItems}
                sidebarExtraNavItems={ApplicationSidebarSelect({location: 'endpoint', projects, applications})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={endpoint !== null ? 'Edit a endpoint' : 'Create a endpoint'}
                        description="Edit the information of your endpoint"
                    />

                    <Form
                        {...(endpoint !== null
                            ? EndpointController.update.form({
                                  endpoint: endpoint[0]?.id,
                              })
                            : EndpointController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {endpoint !== null ? <input type="hidden" name="id" value={endpoint[0]?.id} /> : null}
                                <input type="hidden" name="application_id" value={currentApplication ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={ endpoint !== null ? endpoint[0]?.name : undefined }
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className='w-full'>
                                    <Collapsible open={isOpen} onOpenChange={setIsOpen} className="flex w-full flex-col gap-2">
                                        <div className="flex items-center justify-between gap-4 px-4">
                                            <Label htmlFor="tables_id">Tables</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant="ghost" size="icon" className="size-8">
                                                    <ChevronsUpDown />
                                                    <span className="sr-only">Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selected} name="tables_id" onValueChange={(value) => addTable(value)}>
                                            <SelectTrigger className="w-[180px]">
                                                <SelectValue placeholder="Select a table" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Tables</SelectLabel>
                                                    {tables.map((table) => (
                                                        <SelectItem key={table.id} value={table.id.toString()}>
                                                            {table.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <InputError message={errors.tables} />

                                        <input
                                            type="hidden"
                                            name="columns[]"
                                            value={items}
                                        />
                                        <CollapsibleContent className='grid gap-2 auto-cols-max grid-flow-col w-full'>
                                            {selectedTables?.map((table) => (
                                                <Card key={table}>
                                                    <CardHeader>
                                                        <CardTitle>{tableArr[table]?.name}</CardTitle>
                                                        <CardDescription>
                                                            <Label htmlFor={`${table}_columns_id`}>Columns</Label>
                                                            <Select value={selected} name={`${table}_columns_id`} onValueChange={(value) => addItem(value)}>
                                                                <SelectTrigger className="w-[180px]">
                                                                    <SelectValue placeholder="Select a column" />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectLabel>Columns</SelectLabel>
                                                                        {tableArr[table]?.columns?.map((column) => (
                                                                            <SelectItem key={column.id} value={column.id.toString()}>
                                                                                {column.name}
                                                                            </SelectItem>
                                                                        ))}
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                        </CardDescription>
                                                    </CardHeader>
                                                    <CardContent>
                                                        <InputError message={errors[`${table}_columns_id`]} />
                                                        {items?.map((item, index) => columnTable[item] === table && (
                                                            <div
                                                                className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                                key={index}
                                                            >
                                                                <p className="grow">{ columnArr[item]?.name }</p>
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
                                                    </CardContent>
                                                </Card>
                                            ))}
                                        </CollapsibleContent>
                                    </Collapsible>
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save endpoint</Button>

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
