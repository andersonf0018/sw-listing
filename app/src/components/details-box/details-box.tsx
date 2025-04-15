import Link from "next/link";
import { DetailsItem } from "@/types/details";
import { Button } from "../ui";

interface DetailsBoxProps {
  title: string;
  informations: DetailsItem[];
}

export const DetailsBox = ({ title, informations }: DetailsBoxProps) => {
  return (
    <div className="flex flex-col gap-6">
      <div className="flex flex-col">
        <h2 className="text-lg font-bold">{title}</h2>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-24">
        {informations.map((item) => (
          <div className="flex flex-col" key={item.title}>
            <h3 className="text-md font-bold">{item.title}</h3>
            <hr className="border-t border-gray-300 my-3" />
            {item.children}
          </div>
        ))}
      </div>
      <div>
        <Link href="/">
          <Button className="font-bold uppercase hover:cursor-pointer">
            Back to search
          </Button>
        </Link>
      </div>
    </div>
  );
};
