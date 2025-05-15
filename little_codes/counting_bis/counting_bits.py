import array

def getOneBits(n:int):                              
    meu_array = array.array('i',[])
    search_ones = '1'
    binario = bin(n)[2:]
    for indice, ones in enumerate(binario):
        if (search_ones == ones):
            meu_array.append(indice +1)
    meu_array.insert(0, len(meu_array)) 
    for i in meu_array:
        print(i)  

if __name__ == '__main__':
    n = int(input("Digite o numero: "))
    getOneBits(n)