import ProjectController from '@/actions/App/Http/Controllers/ViewResources/ProjectController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import project from '@/routes/project';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { ProjectNavItems, type projectModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, router, usePage } from '@inertiajs/react';
import { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Project',
        href: project.index().url,
    },
];

export default function ManipulateProject({ project }: { project: projectModel | null }) {
    const { currentCompany } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={project !== null ? 'Edit a project' : 'Create a project'} />

            <ResourcesLayout title="Project" description="Manage your project information" sidebarNavItems={ProjectNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={project !== null ? 'Edit a project' : 'Create a project'}
                        description="Edit the information of your project"
                    />

                    <Form
                        {...(project !== null
                            ? ProjectController.update.form({
                                  project: project?.id,
                              })
                            : ProjectController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        onSuccess={() => router.flushAll()}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {project !== null ? <input type="hidden" name="id" value={project?.id} /> : null}
                                <InputError message={errors.id} />
                                <input type="hidden" name="company_id" value={currentCompany ?? ''} />
                                <InputError message={errors.company_id} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={project?.name}
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save project</Button>

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
