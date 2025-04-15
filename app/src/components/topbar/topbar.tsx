interface TopBarProps {
  title?: string;
}

export const TopBar = ({ title = "SWStarter" }: TopBarProps) => {
  return (
    <div className="flex flex-row justify-center items-center bg-white shadow-md py-3 mb-12">
      <h1 className="text-xl text-primary font-bold">{title}</h1>
    </div>
  );
};