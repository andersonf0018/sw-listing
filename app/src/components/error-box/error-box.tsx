import Link from "next/link";
import { Button } from "../ui";

interface ErrorBoxProps {
  error: string;
}

export const ErrorBox = ({ error }: ErrorBoxProps) => {
  return (
    <div className="flex flex-col justify-center items-center h-full gap-5">
      <div className="text-red-600 text-2xl font-bold text-center">
        Oops! Something went wrong: <br/>
        <span className="text-black">{error}</span>
      </div>
      <Link href="/">
        <Button className="font-bold uppercase hover:cursor-pointer">
          Back to search
        </Button>
      </Link>
    </div>
  );
};
