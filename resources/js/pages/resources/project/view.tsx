import HeadingSmall from "@/components/heading-small"
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item"
import AppLayout from "@/layouts/app-layout"
import ResourcesLayout from "@/layouts/resources/layout"
import project from "@/routes/project"
import { BreadcrumbItem } from "@/types"
import { projectModel, ProjectNavItems } from "@/types/resources.d"
import { Head } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Project',
        href: project.index().url
    }
]

export default function ViewProject({project}: {project: projectModel[]}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View project" />

            <ResourcesLayout title="Project" description="Manage your project information" sidebarNavItems={ProjectNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View project"
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{project[0]?.name}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    )
}
