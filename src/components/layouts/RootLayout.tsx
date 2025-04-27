
import React from "react";
import Navbar from "../ui/custom/Navbar";
import Footer from "../ui/custom/Footer";

interface RootLayoutProps {
  children: React.ReactNode;
}

const RootLayout = ({ children }: RootLayoutProps) => {
  return (
    <div className="min-h-screen flex flex-col">
      <Navbar />
      <main className="flex-grow">{children}</main>
      <Footer />
    </div>
  );
};

export default RootLayout;
