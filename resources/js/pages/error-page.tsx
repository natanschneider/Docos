import { dashboard, login, register } from "@/routes";
import { SharedData } from "@/types";
import { Head, Link, usePage } from "@inertiajs/react";

export default function ErrorPage({ status }: { status: number }) {
    const { auth } = usePage<SharedData>().props

    const title = {
        100: { code: 100, message: 'Continue' },
        101: { code: 101, message: 'Switching Protocols' },
        102: { code: 102, message: 'Processing' },
        103: { code: 103, message: 'Early Hints'},
        200: { code: 200, message: 'OK' },
        201: { code: 201, message: 'Created' },
        202: { code: 202, message: 'Accepted' },
        203: { code: 203, message: 'Non-Authoritative Information' },
        204: { code: 204, message: 'No Content' },
        206: { code: 206, message: 'Partial Content' },
        205: { code: 205, message: 'Reset Content' },
        207: { code: 207, message: 'Multi-Status' },
        208: { code: 208, message: 'Already Reported' },
        214: { code: 214, message: 'Transformation Applied' },
        226: { code: 226, message: 'IM Used' },
        300: { code: 300, message: 'Multiple Choices' },
        301: { code: 301, message: 'Moved Permanently' },
        302: { code: 302, message: 'Found' },
        303: { code: 303, message: 'See Other' },
        304: { code: 304, message: 'Not Modified' },
        305: { code: 305, message: 'Use Proxy' },
        307: { code: 307, message: 'Temporary Redirect' },
        308: { code: 308, message: 'Permanent Redirect' },
        400: { code: 400, message: 'Bad Request' },
        401: { code: 401, message: 'Unauthorized' },
        402: { code: 402, message: 'Payment Required' },
        403: { code: 403, message: 'Forbidden' },
        404: { code: 404, message: 'Not Found' },
        405: { code: 405, message: 'Method Not Allowed' },
        406: { code: 406, message: 'Not Acceptable' },
        407: { code: 407, message: 'Proxy Authentication Required' },
        408: { code: 408, message: 'Request Timeout' },
        409: { code: 409, message: 'Conflict' },
        410: { code: 410, message: 'Gone' },
        411: { code: 411, message: 'Length Required' },
        412: { code: 412, message: 'Precondition Failed' },
        413: { code: 413, message: 'Payload Too Large' },
        414: { code: 414, message: 'Request-URI Too Long' },
        415: { code: 415, message: 'Unsupported Media Type' },
        416: { code: 416, message: 'Request Range Not Satisfiable' },
        417: { code: 417, message: 'Expectation Failed' },
        418: { code: 418, message: 'I’m a teapot' },
        419: { code: 419, message: 'Page Expired' },
        420: { code: 420, message: 'Enhance Your Calm' },
        421: { code: 421, message: 'Misdirected Request' },
        422: { code: 422, message: 'Unprocessable Entity' },
        423: { code: 423, message: 'Locked' },
        424: { code: 424, message: 'Failed Dependency' },
        425: { code: 425, message: 'Too Early' },
        426: { code: 426, message: 'Upgrade Required' },
        428: { code: 428, message: 'Precondition Required' },
        429: { code: 429, message: 'Too Many Requests' },
        431: { code: 431, message: 'Request Header Fields Too Large' },
        444: { code: 444, message: 'No Response' },
        450: { code: 450, message: 'Blocked by Windows Parental Controls' },
        451: { code: 451, message: 'Unavailable For Legal Reasons' },
        495: { code: 495, message: 'SSL Certificate Error' },
        496: { code: 496, message: 'SSL Certificate Required' },
        497: { code: 497, message: 'HTTP Request Sent to HTTPS Port' },
        498: { code: 498, message: 'Token expired/invalid' },
        499: { code: 499, message: 'Client Closed Request' },
        500: { code: 500, message: 'Internal Server Error' },
        501: { code: 501, message: 'Not Implemented' },
        502: { code: 502, message: 'Bad Gateway' },
        503: { code: 503, message: 'Service Unavailable' },
        504: { code: 504, message: 'Gateway Timeout' },
        506: { code: 506, message: 'Variant Also Negotiates' },
        507: { code: 507, message: 'Insufficient Storage' },
        508: { code: 508, message: 'Loop Detected' },
        509: { code: 509, message: 'Bandwidth Limit Exceeded' },
        510: { code: 510, message: 'Not Extended' },
        511: { code: 511, message: 'Network Authentication Required' },
        521: { code: 521, message: 'Web Server Is Down' },
        522: { code: 522, message: 'Connection Timed Out' },
        523: { code: 523, message: 'Origin Is Unreachable' },
        525: { code: 525, message: 'SSL Handshake Failed' },
        530: { code: 530, message: 'Site Frozen' },
        599: { code: 599, message: 'Network Connect Timeout Error' },
    }[status];

    const description = {
        100: "The server received your request and is working on it—hang tight!",
        101: "The server is switching to a different communication protocol.",
        102: "The server is processing your request; it'll take a moment.",
        103: "The server is sending some early hints to help speed things up.",
        200: "Perfect! Your request succeeded and everything is working as expected.",
        201: "Great! Your request succeeded and a new resource was created.",
        202: "Your request was accepted and is being processed in the background.",
        203: "Your request succeeded, but the information came from a third party.",
        204: "Your request succeeded, but there's no content to send back.",
        205: "Your request succeeded—now refresh the page to see the changes.",
        206: "Your request succeeded, but only part of the resource is being sent.",
        207: "Multiple things happened—some succeeded and some had issues.",
        208: "This resource was already reported in a previous request.",
        214: "A transformation was applied to your response.",
        226: "An instance manipulation was used to fulfill your request.",
        300: "There are multiple options available—please choose one.",
        301: "This resource moved permanently; your browser should update its bookmark.",
        302: "This resource moved temporarily to a different location.",
        303: "Check a different URL for the information you're looking for.",
        304: "The resource hasn't changed since you last checked—no need to download it again.",
        305: "You need to use a proxy to access this resource.",
        307: "This resource temporarily moved to a different location (same method).",
        308: "This resource permanently moved to a different location (same method).",
        400: "Your request was malformed or invalid—please check and try again.",
        401: "You need to log in or provide credentials to access this.",
        402: "Payment is required to access this resource.",
        403: "You don't have permission to access this resource.",
        404: "The resource you're looking for doesn't exist or was removed.",
        405: "The method you used isn't allowed for this resource.",
        406: "The server can't provide the format you requested.",
        407: "You need to authenticate with a proxy server.",
        408: "Your request took too long—the server gave up waiting.",
        409: "Your request conflicts with the current state of the resource.",
        410: "This resource is gone and won't come back.",
        411: "You need to include a Content-Length header with your request.",
        412: "A condition you set wasn't met by the server.",
        413: "Your request is too large—try sending less data.",
        414: "The URL you provided is too long.",
        415: "The server doesn't support the file type you sent.",
        416: "The range you requested can't be satisfied.",
        417: "The server can't meet the expectations in your request.",
        418: "I'm a teapot—this is a playful error (the server refuses to brew coffee!).",
        419: "Your session expired—please log in again.",
        420: "You're making requests too aggressively—slow down!",
        421: "Your request was sent to the wrong server.",
        422: "Your request is well-formed but contains semantic errors.",
        423: "The resource you're trying to access is locked.",
        424: "Your request failed because it depended on another failed request.",
        425: "Your request is too early—the server isn't ready yet.",
        426: "You need to upgrade to a different protocol.",
        428: "The server requires a specific condition to be met.",
        429: "You're making too many requests—slow down and try again later.",
        431: "Your request headers are too large.",
        444: "The server closed the connection without responding.",
        450: "This content is blocked by Windows Parental Controls.",
        451: "This resource is unavailable for legal reasons.",
        495: "There's an SSL certificate error.",
        496: "An SSL certificate is required.",
        497: "You sent an HTTP request to an HTTPS port.",
        498: "Your authentication token has expired or is invalid.",
        499: "You closed the request before the server could respond.",
        500: "Something went wrong on the server—it's not your fault!",
        501: "The server doesn't support the functionality you requested.",
        502: "The server received an invalid response from another server.",
        503: "The server is temporarily unavailable—try again later.",
        504: "The server didn't get a response in time from another server.",
        506: "The server has conflicting variant negotiation settings.",
        507: "The server doesn't have enough storage to complete your request.",
        508: "The server detected an infinite loop while processing your request.",
        509: "You've exceeded the bandwidth limit for this resource.",
        510: "The server requires extensions to fulfill your request.",
        511: "You need to authenticate with the network before accessing this.",
        521: "The web server is down or unreachable.",
        522: "The connection to the server timed out.",
        523: "The origin server is unreachable.",
        525: "The SSL handshake between servers failed.",
        530: "The website is temporarily frozen or suspended.",
        599: "The network connection timed out—there's a connectivity issue.",
    }[status];

    return (
        <>
            <Head title={`${title?.code} - ${title?.message}`}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center p-6">
                <div className="mt-8 flex flex-col w-screen items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0">
                    <main className="flex flex-col w-screen items-center justify-center">
                        <h1 className="text-4xl font-bold leading-tight tracking-tight">{`${title?.code} - ${title?.message}`}</h1>
                        <p className="mt-4 text-muted-foreground">{description}</p>
                    </main>
                    <div className="mt-8 w-screen flex flex-col items-center justify-center gap-4">
                        <img src={`https://http.cat/${status}`} alt={`${title?.code} - ${title?.message}`} />
                    </div>
                    <div className="mt-8 w-screen text-sm">
                        <div className="flex items-center justify-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={dashboard()}
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={login()}
                                        className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={register()}
                                        className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    )
}
