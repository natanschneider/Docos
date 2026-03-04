import ScreenController from '@/actions/App/Http/Controllers/ViewResources/ScreenController';
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
import screen from '@/routes/screen';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { columnModel, projectModel, screenModel, ScreenNavItems, tableModel, type applicationModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, router, usePage } from '@inertiajs/react';
import { ChevronsUpDown } from 'lucide-react';
import React, { useRef } from 'react';
import { MDXEditorMethods } from '@mdxeditor/editor';
import TextEditor from '@/components/text-editor';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Screen',
        href: screen.index().url,
    },
];

export default function ManipulateScreen({
    screen,
    doc,
    tables,
    projects,
    applications,
}: {
    screen: screenModel[] | null;
    doc: string;
    tables: tableModel[];
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const { currentApplication } = usePage<SharedData>().props;
    const [isOpen, setIsOpen] = React.useState(true);
    const name = useRef<HTMLInputElement>(null);
    const editorRef = useRef<MDXEditorMethods>(null);
    const [detached, setDetached] = React.useState<string[]>([]);
    const [items, setItems] = React.useState<string[]>(() => {
        if (!screen || !screen[0]?.columns) return [];

        const uniqueItems = Array.from(new Set(
            screen[0].columns
            .map((col) => col.id.toString())
        ));
        console.log(uniqueItems);
        return uniqueItems;
    });
    const [selectedTables, setSelectedTables] = React.useState<string[]>(() => {
        if (!screen || !screen[0]?.columns) return [];

        const uniqueItems = Array.from(new Set(
            screen[0].columns
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
        setDetached(detached.filter((d) => d !== item));

        setSelected(undefined);
    };

    const addTable = (item: string) => {
        if (! selectedTables?.includes(item)) {
            setSelectedTables([...selectedTables??[], item]);
        }
    };

    const removeItem = (item: string) => {
        setItems(items?.filter((i) => i !== item));
        if (!detached.includes(item)) {
            setDetached([...detached, item]);
        }
        setSelected(undefined);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={screen !== null ? 'Edit a screen' : 'Create a screen'} />

            <ResourcesLayout
                title="Screen"
                description="Manage your screen information"
                sidebarNavItems={ScreenNavItems}
                sidebarExtraNavItems={ApplicationSidebarSelect({location: 'screen', projects, applications})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={screen !== null ? 'Edit a screen' : 'Create a screen'}
                        description="Edit the information of your screen"
                    />

                    <Form
                        {...(screen !== null
                            ? ScreenController.update.form({
                                  screen: screen[0]?.id,
                              })
                            : ScreenController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        onSuccess={() => router.flushAll()}
                        className="space-y-6"
                        transform={data => ({...data, markdown: editorRef.current?.getMarkdown()})}
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {screen !== null ? <input type="hidden" name="id" value={screen[0]?.id} /> : null}
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
                                        defaultValue={ screen !== null ? screen[0]?.name : undefined }
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

                                        {items && items.length > 0 && items.map((item, index) => (
                                            <input key={index} type="hidden" name="columns[]" value={item} />
                                        ))}
                                        {detached && detached.length > 0 && detached.map((item, index) => (
                                            <input key={index} type="hidden" name="detach_columns[]" value={item} />
                                        ))}
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

                                <div className="w-full">
                                    <TextEditor editorRef={editorRef} markdown={doc ?? ''} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save screen</Button>

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
