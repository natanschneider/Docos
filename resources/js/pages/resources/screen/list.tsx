import ApplicationSidebarSelect from '@/components/application-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as ScreenRoute from '@/routes/screen';
import { type BreadcrumbItem } from '@/types';
import { applicationModel, projectModel, ScreenNavItems, type screenModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';
import { Button } from "@/components/ui/button"
import { ButtonGroup } from "@/components/ui/button-group"
import { Field, FieldLabel } from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import { useMemo, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Screen',
        href: ScreenRoute.index().url,
    },
];

export default function ListScreens({ screens, projects, applications }: { screens: screenModel[]; projects: projectModel[]; applications: applicationModel[] }) {
    const [query, setQuery] = useState('');

    const filtered = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return screens;

        return screens.filter((screen) => {
            return screen.name.toLowerCase().includes(q);
        })
    }, [screens, query]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of screens" />

            <ResourcesLayout
                title="Screens"
                description="Manage your screens information"
                sidebarNavItems={ScreenNavItems}
                sidebarExtraNavItems={ApplicationSidebarSelect({location: 'screen', projects, applications})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    {screens.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Screens Found</h3>
                            <p className="text-sm text-muted-foreground">There are no screens available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a screen to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Screens" description="List of all Screens" />

                            <Field>
                                <FieldLabel htmlFor="input-button-group">Search</FieldLabel>
                                <ButtonGroup>
                                    <Input
                                        id="input-button-group"
                                        placeholder="Type to search..."
                                        value={query}
                                        onChange={(e) => setQuery(e.target.value)}
                                    />
                                    <Button variant="outline">Search</Button>
                                </ButtonGroup>
                            </Field>

                            {filtered.map((screen) => (
                                <ResourceListItem
                                    key={screen.id}
                                    resource={{
                                        id: screen.id,
                                        name: screen.name,
                                        view_url: ScreenRoute.show(screen.id).url,
                                        edit_url: ScreenRoute.edit(screen.id).url,
                                        delete_url: ScreenRoute.destroy(screen.id).url,
                                        list_url: ScreenRoute.index().url,
                                    }}
                                />
                            ))}
                        </>
                    )}
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
