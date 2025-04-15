import Link from "next/link";
import { Button } from "../ui";

interface ResultsItemProps {
  name: string;
  id: string;
  type: string;
}

export const ResultsItem = ({ name, id, type }: ResultsItemProps) => {
  const detailsUrl = type === "person" ? `/people/${id}` : `/movies/${id}`;

  return (
    <div className="flex flex-col items-center w-full">
      <div className="flex flex-row justify-between items-center w-full">
        <h3 className="text-lg font-bold">{name}</h3>
        <Link href={detailsUrl}>
          <Button className="font-bold cursor-pointer">SEE DETAILS</Button>
        </Link>
      </div>
      <hr className="border-t border-gray-200 w-full mt-3" />
    </div>
  );
};
