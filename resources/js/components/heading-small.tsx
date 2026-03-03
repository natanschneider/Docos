import { Link } from "@inertiajs/react";
import { PencilIcon, TrashIcon } from "lucide-react";
import { Button } from "./ui/button";
import { useState } from "react";
import { AutoCloseAlert } from "./auto-close-alert";
import axios from "axios";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "./ui/alert-dialog";

export default function HeadingSmall({
    title,
    description,
    editUrl,
    deleteUrl,
    id
}: {
    title: string;
    description?: string;
    editUrl?: string;
    deleteUrl?: string;
    id?: number;
}) {
    const [isVisible, setIsVisible] = useState(true);
    const [isOpen, setIsOpen] = useState(false);
    const [alertInfo, setAlertInfo] = useState<{
        type: 'success' | 'error';
        message: string;
        subMessage: string;
    }>({
        type: 'success',
        message: '',
        subMessage: '',
    });

    const deleteItem = async (delete_url: string, id: number) => {
        try {
            const response = await axios.delete(delete_url, {
                data: {
                    id: id,
                },
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                withCredentials: true,
            });

            if (response.status === 200) {
                setAlertInfo({
                    type: 'success',
                    message: 'Success',
                    subMessage: 'The resource has been deleted',
                });
                setIsOpen(true);
                setIsVisible(false);
            }
        } catch (error) {
            let msg = 'Something went wrong';
            let subMsg = 'Please try again later';

            if (axios.isAxiosError(error) && error.response?.data) {
                msg = error.response.data.message || msg;
                subMsg = error.response.data.errors || subMsg;
            }

            setAlertInfo({
                type: 'error',
                message: msg,
                subMessage: subMsg,
            });
            setIsOpen(true);
        }
    };

    return (
        <header className="flex gap-2 items-center">
            {isVisible && (
                <>
                    <div className="flex flex-col gap-1">
                        <h3 className="mb-0.5 text-base font-medium">{title}</h3>
                        {description && <p className="text-sm text-muted-foreground">{description}</p>}
                    </div>
                    {(editUrl || deleteUrl) && (
                        <div className="flex gap-2 ml-4 items-center">
                            {editUrl && (
                                <Link href={editUrl}>
                                    <PencilIcon className="w-4 h-4" />
                                </Link>
                            )}
                            {deleteUrl && id && (
                                <AlertDialog>
                                    <AlertDialogTrigger asChild>
                                        <Button variant="link">
                                            <TrashIcon className="w-4 h-4" />
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                                            <AlertDialogDescription>
                                                This action cannot be undone. This will permanently delete and remove the data.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                                            <AlertDialogAction className="text-destructive" onClick={() => deleteItem(deleteUrl, id)}>
                                                Delete
                                            </AlertDialogAction>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            )}
                        </div>
                    )}
                </>
            )}
            <AutoCloseAlert
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
                title={alertInfo.message}
                description={alertInfo.subMessage}
                autoCloseDelay={3000}
                variant={alertInfo.type}
            />
        </header>
    );
}
