import java.util.Scanner;

public class CaseStudy2 {
    public static void main(String[] args) {
        System.out.println("PERTEMUAN 5 - STUDY KASUS 2 - MUHAMMAD RAFLI ARYANSYAH - 231011401531");

        int defaultInput = 0;
        String fullName = "";
        String yaAtauTidak = "";
        String status = "";

        Scanner input = new Scanner(System.in);

        System.out.println("1. Input Nama");
        System.out.println("2. Close");
        System.out.print("Pilih : ");
        defaultInput = input.nextInt();
        input.nextLine();

        switch (defaultInput) {
            case 1:
                System.out.print("Masukan Nama : ");
                fullName = input.nextLine();

                System.out.print("Hi " + fullName + ", Apakah anda ingin bergabung di group (Y/T) : ");
                yaAtauTidak = input.nextLine();

                if (yaAtauTidak.equalsIgnoreCase("Y")) {
                    status = "Bergabung di group";
                } else {
                    status = "Tidak bergabung di group";
                }
                break;

            case 2:
                fullName = "Tidak ada nama";
                status = "Tidak ada status";
                break;

            default:
                System.out.println("Pilihan tidak valid.");
                return;
        }
        System.out.println("\n--- OUTPUT ---");
        System.out.println("Nama   : " + fullName);
        System.out.println("Status : " + status);
    }
}
