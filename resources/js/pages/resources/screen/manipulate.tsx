import ScreenController from '@/actions/App/Http/Controllers/ViewResources/ScreenController';
import ApplicationSidebarSelect from '@/components/application-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import screen from '@/routes/screen';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { projectModel, screenModel, ScreenNavItems, tableModel, type applicationModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import { ChevronsUpDown } from 'lucide-react';
import React, { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Screen',
        href: screen.index().url,
    },
];

export default function ManipulateScreen({
    screen,
    tables,
    projects,
    applications,
}: {
    screen: screenModel[] | null;
    tables: tableModel[];
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const { currentApplication } = usePage<SharedData>().props;
    const [isOpen, setIsOpen] = React.useState(true);
    const name = useRef<HTMLInputElement>(null);
    const [items, setItems] = React.useState<string[]>(screen !== null ? screen[0]?.columns.map((col) => col.id.toString()) : []);
    const [selectedTables, setSelectedTables] = React.useState<string[]>(screen !== null ? screen[0]?.columns.map((col) => col.table_id.toString()) : []);
    const [selected, setSelected] = React.useState<string | undefined>(undefined);

    const tableArr = tables.reduce(
        (acc, tables) => {
            acc[tables.id] = tables;
            return acc;
        },
        {} as Record<string, tableModel>,
    );

    const columnTable = tables.reduce((acc, table) => {
        const columns = table?.columns;
        columns?.forEach((column) => {
            if (column.id) {
                acc[column.id.toString()] = table.id.toString();
            }
        });
        return acc;
    }, {} as Record<string, string>);

    const addItem = (item: string) => {
        if (!items?.includes(item)) {
            setItems([...items??[], item]);
            if (! selectedTables?.includes(columnTable[item])) {
                setSelectedTables([...selectedTables??[], columnTable[item]]);
            }
        }

        setSelected(undefined);
    };

    const removeItem = (item: string) => {
        setItems(items?.filter((i) => i !== item));
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
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
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
                                                    {tables.map((table) => (
                                                        <SelectItem key={table.id} value={table.id.toString()}>
                                                            {table.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <InputError message={errors.databases} />

                                        <input type="hidden" name="databases[]" value={items} />
                                        <CollapsibleContent className="mt-3 flex flex-col gap-2">
                                            {items?.map((item, index) => (
                                                <div className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                                    <p className="grow">{tableArr[item]}</p>
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
