import { Button } from "@/components";
import Link from "next/link";

const NotFound = () => {
  return (
    <div className="flex flex-col justify-center items-center h-full gap-5">
      <div className="flex flex-col gap-2">
        <p className="text-4xl font-bold text-center">404</p>
        <p className="text-red-600 text-2xl font-bold text-center">
          Oops! Page not found
        </p>
      </div>
      <Link href="/">
        <Button className="font-bold uppercase hover:cursor-pointer">
          Back to search
        </Button>
      </Link>
    </div>
  );
};

export default NotFound;
