import ColumnController from '@/actions/App/Http/Controllers/ViewResources/ColumnController';
import ColumnSidebarSelect from '@/components/column-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronsUpDown } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import column from '@/routes/column';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { columnModel, ColumnNavItems, tableModel, databaseModel, typeModel, constraintsModel } from '@/types/resources.d';
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
    types,
    constraints
}: {
    column: columnModel[];
    tables: tableModel[];
    databases: databaseModel[];
    primaryKey: columnModel[];
    foreignKey: columnModel[];
    types: typeModel[];
    constraints: constraintsModel[];
}) {
    const { currentTable } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);
    const [isConstraintOpen, setIsConstraintOpen] = React.useState(true);
    const [constraint, setConstraint] = React.useState<string[]>(column !== null && column[0]?.constraints
        ? column[0]?.constraints?.map((constraint) => constraint.id.toString())
        : []
    );
    const [selectedConstraint, setSelectedConstraint] = React.useState<string | undefined>(undefined);

    const constraintArr = constraints.reduce(
        (acc, constraint) => {
            acc[constraint.id] = constraint.name;
            return acc;
        },
        {} as Record<string, string>,
    );


    const addConstraint = (item: string) => {
        if (!constraint.includes(item)) {
            setConstraint([...constraint, item]);
        }

        setSelectedConstraint('');
    };

    const removeConstraint = (item: string) => {
        setConstraint(constraint.filter((i) => i !== item));
        setSelectedConstraint('');
    };

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

                                <div className="grid gap-2">
                                    <Label htmlFor="type_id">Type</Label>

                                    <Select name="type_id" required defaultValue={column !== null ? column[0]?.type_id?.toString() : undefined}>
                                        <SelectTrigger className="w-[180px]">
                                            <SelectValue placeholder="Select an type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectLabel>Types</SelectLabel>
                                                {types.map((type) => (
                                                    <SelectItem key={type.id} value={type.id.toString()}>
                                                        {type.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>

                                    <InputError message={errors.type_id} />
                                </div>

                                <div className="grid gap-2">
                                    <Collapsible open={isConstraintOpen} onOpenChange={setIsConstraintOpen} className="flex w-[350px] flex-col gap-2">
                                        <div className="flex items-center justify-between gap-4 px-4">
                                            <Label htmlFor="constraints_id">Constraints</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant="ghost" size="icon" className="size-8">
                                                    <ChevronsUpDown />
                                                    <span className="sr-only">Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selectedConstraint} name="database_id" onValueChange={(value) => addConstraint(value)}>
                                            <SelectTrigger className="w-[180px]">
                                                <SelectValue placeholder="Select an constraint" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Constraints</SelectLabel>
                                                    {constraints.map((constraint) => (
                                                        <SelectItem key={constraint.id} value={constraint.id.toString()}>
                                                            {constraint.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <InputError message={errors.constraints} />

                                        <input type="hidden" name="databases[]" value={constraint} />
                                        <CollapsibleContent className="mt-3 flex flex-col gap-2">
                                            {constraint.map((item, index) => (
                                                <div className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                                    <p className="grow">{constraintArr[item]}</p>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                        onClick={() => removeConstraint(item)}
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            ))}
                                        </CollapsibleContent>
                                    </Collapsible>
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
