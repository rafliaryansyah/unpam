import java.util.*;

// MUHAMMAD RAFLI ARYANSYAH - 231011401531 - DATA MINING
public class NaiveBayes {

    // data pelatihan (data training) yang digunakan untuk menghitung probabilitas.
    // format: [Cuaca, Suhu, Kelembaban, Angin, Label (Ya/Tidak bermain golf)]
    static String[][] dataLatih = {
            {"Cerah", "Panas", "Tinggi", "Lemah", "Tidak"},
            {"Cerah", "Panas", "Tinggi", "Kuat", "Tidak"},
            {"Berawan", "Panas", "Tinggi", "Lemah", "Ya"},
            {"Hujan", "Sedang", "Tinggi", "Lemah", "Ya"},
            {"Hujan", "Dingin", "Normal", "Lemah", "Ya"},
            {"Hujan", "Dingin", "Normal", "Kuat", "Tidak"},
            {"Berawan", "Dingin", "Normal", "Kuat", "Ya"},
            {"Cerah", "Sedang", "Tinggi", "Lemah", "Tidak"},
            {"Cerah", "Dingin", "Normal", "Lemah", "Ya"},
            {"Hujan", "Sedang", "Normal", "Lemah", "Ya"},
            {"Cerah", "Sedang", "Normal", "Kuat", "Ya"},
            {"Berawan", "Sedang", "Tinggi", "Kuat", "Ya"},
            {"Berawan", "Panas", "Normal", "Lemah", "Ya"},
            {"Hujan", "Sedang", "Tinggi", "Kuat", "Tidak"}
    };

    // daftar atribut yang digunakan untuk klasifikasi
    static String[] atribut = {"Cuaca", "Suhu", "Kelembaban", "Angin"};

    public static void main(String[] args) {
        // data uji: data baru yang ingin kita prediksi apakah main golf atau tidak
        String[] dataUji = {"Cerah", "Dingin", "Tinggi", "Kuat"};

        // menjalankan prediksi
        String hasilPrediksi = prediksi(dataUji);
        System.out.println("Prediksi hasil bermain golf: " + hasilPrediksi);
    }

    /**
     * gungsi untuk memprediksi kelas dari data uji berdasarkan algoritma Naive Bayes
     */
    public static String prediksi(String[] dataMasuk) {
        // save/menyimpan hasil probabilitas untuk masing-masing kelas (Ya / Tidak)
        Map<String, Double> peluangPerKelas = new HashMap<>();

        // save/menyimpan jumlah kemunculan setiap kelas
        Map<String, Integer> jumlahKelas = new HashMap<>();

        int totalData = dataLatih.length;

        // 1. menghitung jumlah data untuk setiap kelas
        for (String[] baris : dataLatih) {
            String label = baris[4]; // Kolom ke-5 adalah label kelas, sebab array index dimulai dari 0
            jumlahKelas.put(label, jumlahKelas.getOrDefault(label, 0) + 1);
        }

        // 2. menghitung probabilitas tiap kelas dengan mempertimbangkan tiap atribut
        for (String label : jumlahKelas.keySet()) {
            // probabilitas awal (prior probability) untuk kelas ini
            double peluang = (double) jumlahKelas.get(label) / totalData;

            // iterasi setiap atribut
            for (int i = 0; i < dataMasuk.length; i++) {
                int cocok = 0;
                int totalLabel = 0;

                // hitung jumlah kemunculan nilai atribut yang sama dengan data uji untuk kelas tersebut
                for (String[] baris : dataLatih) {
                    if (baris[4].equals(label)) {
                        totalLabel++;
                        if (baris[i].equals(dataMasuk[i])) {
                            cocok++;
                        }
                    }
                }

                /**
                 * Laplace Smoothing digunakan untuk menghindari probabilitas 0
                 * Jika suatu nilai atribut tidak muncul dalam data pelatihan untuk kelas tertentu,
                 * maka tanpa smoothing probabilitas menjadi 0, dan semua hasil dikali 0.
                 * Formula smoothing:
                 *     peluangAtribut = (cocok + 1) / (totalLabel + jumlah nilai unik)
                 * Dalam kasus ini, karena tidak menggunakan daftar nilai unik, kita cukup tambah 1.
                 */
                double peluangAtribut = (cocok + 1.0) / (totalLabel + 1.0);
                peluang *= peluangAtribut; // Kalikan ke total probabilitas kelas
            }

            // save hasil akhir peluang untuk kelas ini
            peluangPerKelas.put(label, peluang);
        }

        // 3. get kelas dengan probabilitas tertinggi sebagai hasil prediksi
        return peluangPerKelas.entrySet().stream()
                .max(Map.Entry.comparingByValue())
                .get().getKey();
    }
}
