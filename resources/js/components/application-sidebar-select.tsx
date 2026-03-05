import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { changeApplication as changeApp } from '@/routes';
import { type SharedData } from '@/types';
import { applicationModel, projectModel } from '@/types/resources';
import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function ApplicationSidebarSelect({
    location,
    projects,
    applications
}: {
    location: string;
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const { currentApplication, currentProject } = usePage<SharedData>().props;
    const [application, setApplication] = useState<string>(currentApplication?.toString() ?? applications[0]?.id?.toString());
    const [project, setProject] = useState<string>(currentProject?.toString() ?? projects[0]?.id?.toString());

    const changeProject = (value: string) => {
        setProject(value);
        router.flushAll();
        router.get(changeApp({ location, project: value, application }));
    };

    const changeApplication = (value: string) => {
        setApplication(value);
        router.flushAll();
        router.get(changeApp({ location, project, application: value }));
    };

    return (
        <div className='w-full my-4'>
            <div className="justify-start">
                <Label htmlFor="project">Project</Label>

                <input type="hidden" name="project" value={project} />
                <Select onValueChange={(value) => changeProject(value)} defaultValue={project}>
                    <SelectTrigger className="w-45">
                        <SelectValue placeholder="Select a project" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Project</SelectLabel>
                            {projects.map((project) => (
                                <SelectItem key={project.id} value={project.id.toString()}>
                                    {project.name}
                                </SelectItem>
                            ))}
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>

            <div className="justify-start">
                <Label htmlFor="application">Application</Label>

                <input type="hidden" name="application" value={application} />
                <Select onValueChange={(value) => changeApplication(value)} defaultValue={application}>
                    <SelectTrigger className="w-45">
                        <SelectValue placeholder="Select an application" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Application</SelectLabel>
                            {applications.map((application) => (
                                <SelectItem key={application.id} value={application.id.toString()}>
                                    {application.name}
                                </SelectItem>
                            ))}
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
        </div>
    );
}
