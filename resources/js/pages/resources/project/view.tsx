import HeadingSmall from "@/components/heading-small"
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item"
import { Label } from "@/components/ui/label"
import AppLayout from "@/layouts/app-layout"
import ResourcesLayout from "@/layouts/resources/layout"
import application from "@/routes/application"
import company from "@/routes/company"
import project from "@/routes/project"
import { BreadcrumbItem } from "@/types"
import { projectModel, ProjectNavItems } from "@/types/resources.d"
import { Head, Link } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Project',
        href: project.index().url
    }
]

const projectRoute = project;

export default function ViewProject({project}: {project: projectModel[]}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View project" />

            <ResourcesLayout title="Project" description="Manage your project information" sidebarNavItems={ProjectNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View project"
                        editUrl={projectRoute.edit(project[0]?.id).url}
                    />

                    <div className="space-y-6">
                        {project[0]?.company && (
                            <div className="grid gap-2">
                                <Link href={company.show(project[0].company?.id)}>
                                    <Item variant='outline'>
                                        <ItemContent>
                                            <ItemTitle>Company</ItemTitle>
                                            <ItemDescription>{project[0]?.company?.name}</ItemDescription>
                                        </ItemContent>
                                    </Item>
                                </Link>
                            </div>
                        )}

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{project[0]?.name}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        {project[0]?.applications && project[0]?.applications.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label>Applications</Label>
                                    </div>

                                    {project[0]?.applications.map((item, index) => (
                                        <Link href={application.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                            <p className="grow">{item.name}</p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    )
}
